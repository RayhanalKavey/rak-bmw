<script setup>
import { usePage } from "@inertiajs/vue3";

const { posts } = usePage().props;
</script>

<template>
    <div class="max-w-7xl mx-auto p-6">
        <h1 class="text-3xl font-bold mb-6">Latest Posts</h1>
        <div v-if="posts.length === 0" class="text-center text-gray-500">
            No posts available.
        </div>
        <div
            v-else
            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"
        >
            <div
                v-for="post in posts"
                :key="post.id"
                class="bg-white p-6 rounded-lg shadow-lg"
            >
                <h2 class="text-xl font-semibold">{{ post.title }}</h2>
                <p class="text-gray-600 mt-2">
                    {{ post.content.slice(0, 100) }}...
                </p>
                <div class="mt-4 flex items-center justify-between">
                    <span class="text-sm text-gray-500"
                        >By {{ post.user.username }}</span
                    >
                    <span
                        v-if="post.visibility === 'public'"
                        class="text-green-500"
                        >Public</span
                    >
                    <span v-else class="text-gray-500">Private</span>
                </div>
                <div class="mt-4">
                    <router-link :to="'/posts/' + post.id" class="text-blue-600"
                        >Read more...</router-link
                    >
                </div>
            </div>
        </div>
    </div>
</template>
