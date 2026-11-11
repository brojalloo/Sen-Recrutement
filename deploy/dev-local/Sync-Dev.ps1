param([switch]$Build)

$ErrorActionPreference = 'Stop'
$context = 'desktop-linux'
$compose = Join-Path $PSScriptRoot 'compose.yaml'
$envFile = Join-Path $PSScriptRoot '.env'

function Invoke-Docker {
    & docker --context $context @args
    if ($LASTEXITCODE -ne 0) {
        throw "Docker a echoue (code $LASTEXITCODE)."
    }
}

Invoke-Docker info --format '{{.OSType}}' | Out-Null

if (-not (Test-Path -LiteralPath $envFile)) {
    $bytes = New-Object byte[] 32
    $rng = [System.Security.Cryptography.RandomNumberGenerator]::Create()
    try {
        $rng.GetBytes($bytes)
    } finally {
        $rng.Dispose()
    }
    $value = 'base64:' + [Convert]::ToBase64String($bytes)
    $stream = [System.IO.File]::Open($envFile, [System.IO.FileMode]::CreateNew)
    try {
        $writer = New-Object System.IO.StreamWriter($stream)
        try {
            $writer.WriteLine("SEN_RECRUTEMENT_APP_KEY=$value")
        } finally {
            $writer.Dispose()
        }
    } finally {
        $stream.Dispose()
    }
}

$composeArgs = @('compose', '--env-file', $envFile, '-f', $compose)
$previousImage = $env:DEV_IMAGE
try {
    if ($Build) {
        $env:DEV_IMAGE = 'sen-recrutement-docker-local:local'
        Invoke-Docker @composeArgs up --build --wait --wait-timeout 180
    } else {
        if (-not (Get-Command gh -ErrorAction SilentlyContinue)) {
            throw "Installe GitHub CLI et lance 'gh auth login', ou utilise -Build."
        }

        $repository = 'brojalloo/Sen-Recrutement'
        $endpoint = "repos/$repository/actions/workflows/ci-deploy.yml/runs?branch=dev&event=push&status=success&per_page=1"
        $result = gh api $endpoint --jq '.workflow_runs[0] | [.id, .head_sha] | @tsv'
        if ($LASTEXITCODE -ne 0 -or $result -notmatch '^([0-9]+)\t([a-f0-9]{40})$') {
            throw "Impossible de trouver une execution dev valide. Verifie 'gh auth status' ou utilise -Build."
        }
        $runId = $Matches[1]
        $revision = $Matches[2]
        $image = "sen-recrutement-docker-local:${revision}"

        $installed = Invoke-Docker image ls --quiet $image
        if (-not $installed) {
            $download = Join-Path ([System.IO.Path]::GetTempPath()) ([guid]::NewGuid().ToString())
            New-Item -ItemType Directory -Path $download | Out-Null
            try {
                gh run download $runId --repo $repository --name sen-recrutement-docker-local --dir $download
                if ($LASTEXITCODE -ne 0) {
                    throw "Le telechargement de l'image a echoue. Utilise -Build si l'archive a expire."
                }
                Invoke-Docker load --input (Join-Path $download 'sen-recrutement-image.tar.gz')
            } finally {
                Remove-Item -LiteralPath $download -Recurse -Force
            }
        }

        $labels = Invoke-Docker image inspect $image --format '{{json .Config.Labels}}' | ConvertFrom-Json
        $imageRevision = $labels.'org.opencontainers.image.revision'
        if ($imageRevision -ne $revision) {
            throw "L'image Docker ne correspond pas a la revision validee."
        }
        $env:DEV_IMAGE = $image
        Invoke-Docker @composeArgs up --no-build --pull missing --wait --wait-timeout 180
    }
} finally {
    $env:DEV_IMAGE = $previousImage
}

Write-Host 'Application disponible sur http://127.0.0.1:8093'
