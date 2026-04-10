<table class="min-w-full divide-y divide-gray-200">
    <thead>
        <tr>
            <th class="px-6 py-3 bg-gray-50">اسم الملف</th>
            <th class="px-6 py-3 bg-gray-50">النوع</th>
            <th class="px-6 py-3 bg-gray-50">تحميل</th>
        </tr>
    </thead>
    <tbody>
        @foreach($attachments as $attachment)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap">{{ $attachment->file }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ $attachment->type->label() ?? $attachment->type }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <a href="{{ Storage::url($attachment->file) }}" target="_blank" class="text-blue-600 hover:underline">تحميل</a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
