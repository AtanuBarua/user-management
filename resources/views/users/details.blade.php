<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __($heading) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <button onclick="history.back()" type="button"
                class="float-start text-white bg-purple-700 hover:bg-purple-800 focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-purple-600 dark:hover:bg-purple-700 focus:outline-none dark:focus:ring-purple-800">
                Back
            </button>
            <a href="{{route('dashboard')}}" type="button"
                class="float-end text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-green-800">
                User List
            </a>
            <div class="relative overflow-x-auto">
                @if ($user)
                <form class="max-w-sm mx-auto">
                    <input type="hidden" name="id" value="{{$user->id}}">
                    <div class="mb-5">
                        <label for="name"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name</label>
                        <input disabled name="name" value="{{$user->name}}" type="text" id="name"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                        @error('name')
                        <p class="text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-5">
                        <label for="email"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
                        <input disabled name="email" value="{{$user->email}}" type="email" id="email"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                        @error('email')
                        <p class="text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </form>
                @else
                <h2 class="text-red-500 text-xl text-center">User not found</h2>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
