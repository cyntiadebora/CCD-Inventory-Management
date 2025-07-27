<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Request Approved</title>
</head>
<body style="font-family: Arial, sans-serif; color: #333;">
    <h2>Hello {{ $user->name }},</h2>

    <p>Your item request has been <span style="color: green;"><strong>approved</strong></span>.</p>
    <p><strong>Request Details:</strong></p>
    <ul>
        @foreach ($request->requestItems as $item)
            <li>
                {{ $item->itemVariant->item->name ?? '-' }} - Qty: {{ $item->quantity }}
                @if ($item->itemVariant->size)
                    - Size: {{ $item->itemVariant->size->size_label }}
                @endif
            </li>
        @endforeach
    </ul>

    {{-- Tambahkan bagian ini untuk pesan dari admin --}}
    @if (!empty($adminMessage))
        <p><strong>Message from Admin:</strong></p>
        <div style="background-color: #f8f9fa; padding: 12px; border-left: 4px solid #007bff; margin-bottom: 20px;">
            {{ $adminMessage }}
        </div>
    @endif

    <p>If you have any questions, please contact the inventory team.</p>

    <p>Thank you,<br>
    <strong>CCD Inventory System</strong></p>
</body>
</html>
