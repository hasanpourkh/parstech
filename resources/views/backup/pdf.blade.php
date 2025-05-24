<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <title>پشتیبان PDF</title>
    <style>
        body { font-family: Tahoma, Arial, sans-serif; font-size: 12px; direction: rtl; }
        h2 { color: #0072ff; border-bottom: 1px solid #bbb; padding-bottom: 3px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 18px; }
        th, td { border: 1px solid #888; padding: 4px; }
        th { background: #f0f4ff; }
    </style>
</head>
<body>
@foreach($data as $table => $records)
    <h2>{{ $table }}</h2>
    <table>
        <thead>
            <tr>
            @if(count($records))
                @foreach(array_keys((array)$records[0]) as $key)
                    <th>{{ $key }}</th>
                @endforeach
            @endif
            </tr>
        </thead>
        <tbody>
            @forelse($records as $row)
                <tr>
                    @foreach((array)$row as $cell)
                        <td>{{ $cell }}</td>
                    @endforeach
                </tr>
            @empty
                <tr><td colspan="99">داده‌ای نیست</td></tr>
            @endforelse
        </tbody>
    </table>
@endforeach
</body>
</html>
