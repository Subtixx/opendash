<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ active_tab: 0 }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 shadow sm:rounded-lg bg-base-100 text-base-content">
                <section>
                    <header x-on:click="active_tab = 0">
                        <h2 class="text-lg font-medium" :class="active_tab !== 0 ? 'cursor-pointer' : ''">
                            {{ __('Theme') }}
                        </h2>

                        <p class="mt-1 text-sm" x-show="active_tab === 0">
                            {{ __("Select your preferred theme.") }}
                        </p>
                    </header>
                    <div x-show="active_tab === 0">
                        <x-theme-selector></x-theme-selector>
                    </div>
                </section>
            </div>
            <div class="p-4 sm:p-8 shadow sm:rounded-lg bg-base-100 text-base-content">
                <section>
                    <header x-on:click="active_tab = 1">
                        <h2 class="text-lg font-medium" :class="active_tab !== 1 ? 'cursor-pointer' : ''">
                            {{ __('Profile Information') }}
                        </h2>

                        <p class="mt-1 text-sm" x-show="active_tab === 1">
                            {{ __("Update your account's profile information and email address.") }}
                        </p>
                    </header>

                    <div x-show="active_tab === 1">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </section>
            </div>

            <div class="p-4 sm:p-8 shadow sm:rounded-lg bg-base-100 text-base-content">
                <section>
                    <header x-on:click="active_tab = 2">
                        <h2 class="text-lg font-medium" :class="active_tab !== 2 ? 'cursor-pointer' : ''">
                            {{ __('Update Password') }}
                        </h2>

                        <p class="mt-1 text-sm" x-show="active_tab === 2">
                            {{ __('Ensure your account is using a long, random password to stay secure.') }}
                        </p>
                    </header>

                    <div x-show="active_tab === 2">
                        @include('profile.partials.update-password-form')
                    </div>
                </section>
            </div>

            <div class="p-4 sm:p-8 shadow sm:rounded-lg bg-base-100 text-base-content">
                <section class="space-y-6">
                    <header x-on:click="active_tab = 3">
                        <h2 class="text-lg font-medium" :class="active_tab !== 3 ? 'cursor-pointer' : ''">
                            {{ __('Delete Account') }}
                        </h2>

                        <p class="mt-1 text-sm" x-show="active_tab === 3">
                            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
                        </p>
                    </header>

                    <div x-show="active_tab === 3">
                        @include('profile.partials.delete-user-form')
                    </div>
                </section>
            </div>
        </div>
    </div>
</x-app-layout>
