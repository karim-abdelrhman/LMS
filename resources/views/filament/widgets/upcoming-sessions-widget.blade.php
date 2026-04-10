<x-filament-widgets::widget>
    <x-filament::section>
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">
                    📅 الجلسات القادمة في 7 أيام
                </h3>
                <span class="text-sm text-gray-500 dark:text-gray-400">
                    Next 7 Days
                </span>
            </div>

            @if ($this->hasUpcomingSessions())
                <div class="space-y-3">
                    @foreach ($this->getUpcomingSessions() as $session)
                        <div
                            class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-900 dark:text-white text-base">
                                        {{ $session['case_title'] }}
                                    </p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                        📍 {{ $session['location'] }}
                                    </p>
                                </div>
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                    @if ($session['status_color'] === 'warning') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                    @elseif ($session['status_color'] === 'success')
                                        bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                    @elseif ($session['status_color'] === 'info')
                                        bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                    @elseif ($session['status_color'] === 'danger')
                                        bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                    @else
                                        bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200 @endif
                                ">
                                    {{ $session['status'] }}
                                </span>
                            </div>

                            <div class="grid grid-cols-2 gap-3 text-sm">
                                <div>
                                    <p class="text-gray-500 dark:text-gray-400">⏰ التاريخ والوقت</p>
                                    <p class="font-medium text-gray-900 dark:text-white">
                                        {{ $session['scheduled_at'] }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-gray-500 dark:text-gray-400">⚖️ القاضي</p>
                                    <p class="font-medium text-gray-900 dark:text-white">
                                        {{ $session['judge'] }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <p class="text-gray-600 dark:text-gray-300 font-medium">
                        لا توجد جلسات مجدولة
                    </p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        No upcoming sessions in the next 7 days
                    </p>
                </div>
            @endif
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
