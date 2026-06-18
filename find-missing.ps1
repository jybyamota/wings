$lines = Get-Content "c:\Users\Admin\Downloads\wings-main\menu.php"
foreach ($line in $lines) {
    if ($line -match 'class="menu-item"' -and $line -notmatch 'data-item-image') {
        if ($line -match 'data-item-name="([^"]*)"') {
            Write-Host $Matches[1]
        }
    }
}
