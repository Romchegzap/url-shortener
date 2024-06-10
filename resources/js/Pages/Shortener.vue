<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg px-6 pb-6">

                    <form class="mt-8 space-y-6" @submit.prevent="submitForm">
                        <div class="rounded-md shadow-sm -space-y-px">
                            <div>
                                <input v-model="form.originalUrl" type="text" placeholder="Enter URL"
                                       class="appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                                       required>
                            </div>
                            <div class="text-red-500 pl-1 pt-1"
                                 v-if="form.errors.originalUrl">{{ form.errors.originalUrl }}
                            </div>
                        </div>
                        <div>
                            <button type="submit"
                                    class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Shorten
                            </button>
                        </div>
                    </form>
                    <div v-if="shortUrl" class="text-center mt-4">
                        <p class="text-sm text-gray-600">Short URL: <a :href="shortUrl" class="text-indigo-600 hover:text-indigo-900">{{ shortUrl }}</a></p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, router} from '@inertiajs/vue3';
import {useForm} from '@inertiajs/vue3'

const props = defineProps({
    shortUrl: [String],
    originalUrl: [String]
});

const form = useForm({
    originalUrl: props.originalUrl ?? '',
});

function submitForm() {
    form.post(route('shortener.store'));
}

</script>
