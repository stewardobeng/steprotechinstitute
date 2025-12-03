<x-app-layout>
    <x-slot name="title">Pending Approval</x-slot>

    <!-- PageHeading -->
    <div class="flex flex-wrap justify-between gap-3 items-center">
        <h1 class="text-gray-900 dark:text-white text-4xl font-black leading-tight tracking-[-0.033em]">Pending Approval</h1>
    </div>

    <div class="mt-8">
        <div class="flex flex-1 flex-col items-start justify-between gap-4 rounded-xl border border-orange-200 dark:border-orange-800 bg-orange-50 dark:bg-orange-900/20 p-5">
            <div class="w-full">
                <h2 class="text-lg font-bold text-orange-800 dark:text-orange-400 mb-2">Your registration is pending approval.</h2>
                <p class="text-orange-700 dark:text-orange-300">Please wait for an administrator to approve your affiliate agent registration. Once approved, you will have access to your dashboard and can start earning commissions.</p>
            </div>
        </div>
    </div>
</x-app-layout>
