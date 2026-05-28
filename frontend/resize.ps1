Add-Type -AssemblyName System.Drawing
function Resize-Image {
    param([string]$in, [string]$out, [int]$w, [int]$h)
    $img = [System.Drawing.Image]::FromFile($in)
    $bmp = New-Object System.Drawing.Bitmap($w, $h)
    $g = [System.Drawing.Graphics]::FromImage($bmp)
    $g.InterpolationMode = [System.Drawing.Drawing2D.InterpolationMode]::HighQualityBicubic
    $g.DrawImage($img, 0, 0, $w, $h)
    $g.Dispose()
    $bmp.Save($out, [System.Drawing.Imaging.ImageFormat]::Png)
    $bmp.Dispose()
    $img.Dispose()
}

$dir = "C:\Ricardo\rcgcrm\frontend\public\assets"
if (-Not (Test-Path $dir)) { New-Item -ItemType Directory -Force -Path $dir }

Resize-Image "C:\Users\ricar\.gemini\antigravity-ide\brain\34257916-e912-428f-a7fe-53388d86fa6e\avatar_01_v2_1779927160672.png" "$dir\avatar_01.png" 98 98
Resize-Image "C:\Users\ricar\.gemini\antigravity-ide\brain\34257916-e912-428f-a7fe-53388d86fa6e\avatar_02_v2_1779927173197.png" "$dir\avatar_02.png" 98 98
Resize-Image "C:\Users\ricar\.gemini\antigravity-ide\brain\34257916-e912-428f-a7fe-53388d86fa6e\avatar_04_v2_1779927191157.png" "$dir\avatar_04.png" 98 98
Resize-Image "C:\Users\ricar\.gemini\antigravity-ide\brain\34257916-e912-428f-a7fe-53388d86fa6e\avatar_09_1779927213163.png" "$dir\avatar_09.png" 98 98
Resize-Image "C:\Users\ricar\.gemini\antigravity-ide\brain\34257916-e912-428f-a7fe-53388d86fa6e\avatar_10_1779927226348.png" "$dir\avatar_10.png" 98 98
Resize-Image "C:\Users\ricar\.gemini\antigravity-ide\brain\34257916-e912-428f-a7fe-53388d86fa6e\avatar_11_1779927238010.png" "$dir\avatar_11.png" 98 98
Resize-Image "C:\Users\ricar\.gemini\antigravity-ide\brain\34257916-e912-428f-a7fe-53388d86fa6e\avatar_12_1779927252055.png" "$dir\avatar_12.png" 98 98
Resize-Image "C:\Users\ricar\.gemini\antigravity-ide\brain\34257916-e912-428f-a7fe-53388d86fa6e\avatar_13_1779927271455.png" "$dir\avatar_13.png" 98 98
