<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __($heading) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (!empty(session()->get('alert')['color']) && !empty(session()->get('alert')['message']))
            <div class="flex items-center p-4 mb-4 text-sm text-{{ session()->get('alert')['color'] }}-800 rounded-lg bg-{{ session()->get('alert')['color'] }}-50 dark:bg-gray-800 dark:text-{{ session()->get('alert')['color'] }}-400"
                role="alert">
                <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                    fill="currentColor" viewBox="0 0 20 20">
                    <path
                        d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                </svg>
                <span class="sr-only">Info</span>
                <div>
                    <span class="font-medium">{{ session()->get('alert')['message'] }}</span>
                </div>
            </div>
            @endif

            <div class="relative overflow-x-auto">
                <button onclick="history.back()" type="button"
                class="float-start text-white bg-purple-700 hover:bg-purple-800 focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-purple-600 dark:hover:bg-purple-700 focus:outline-none dark:focus:ring-purple-800">
                Back
                </button>

                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                Name
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Email
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Created At
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Deleted At
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Operation
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($trashed as $trash)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th scope="row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{$trash->name}}
                            </th>
                            <td class="px-6 py-4">
                                {{$trash->email}}
                            </td>
                            <td class="px-6 py-4">
                                {{$trash->created_at}}
                            </td>
                            <td class="px-6 py-4">
                                {{$trash->deleted_at}}
                            </td>
                            <td class="px-6 py-4">
                                <form action="{{route('user.restore', $trash->id)}}" method="POST">
                                    @csrf
                                    <button onclick="return confirm('Are you sure to restore?')" type="submit"
                                        class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                                        Restore
                                    </button>
                                </form>
                                <form action="{{route('user.delete', $trash->id)}}" method="POST">
                                    @csrf @method('DELETE')
                                    <button onclick="return confirm('Are you sure to permanently delete? This can not be undone')" type="submit"
                                        class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">
                                        Permanent Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <p class="text-red-600 text-center">No data found</p>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-6">{{$trashed->links()}}</div>
        </div>
    </div>
</x-app-layout>
