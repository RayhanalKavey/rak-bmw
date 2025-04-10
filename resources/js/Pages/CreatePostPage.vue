<script setup>
import { useForm, usePage } from "@inertiajs/vue3";
import { createToaster } from "@meforma/vue-toaster";

const toaster = createToaster();
const props = defineProps({ tags: Array });
const page = usePage();

const form = useForm({
    title: "",
    content: "",
    visibility: "public",
    image: null,
    tags: [], // Will contain selected tag names
    newTags: "", // Comma-separated new tag names
});

const handleImage = (e) => {
    form.image = e.target.files[0];
};

const submit = () => {
    // Convert comma-separated newTags to array
    const newTagArray = form.newTags
        .split(",")
        .map((tag) => tag.trim())
        .filter((tag) => tag.length > 0);

    // Combine selected existing tags and new tags
    form.tags = [...form.tags, ...newTagArray];

    form.post("/posts-create", {
        onSuccess: () => {
            toaster.success(page.props.flash.message);
        },
    });
};
</script>

<template>
    <div class="max-w-xl mx-auto bg-white p-6 rounded-xl shadow">
        <h1 class="text-2xl font-bold mb-4">Create New Post</h1>
        <form @submit.prevent="submit" class="space-y-4">
            <div>
                <label class="block mb-1 font-medium">Title</label>
                <input
                    v-model="form.title"
                    class="w-full border rounded p-2"
                    type="text"
                    placeholder="Enter post title"
                />
            </div>

            <div>
                <label class="block mb-1 font-medium">Content</label>
                <textarea
                    v-model="form.content"
                    class="w-full border rounded p-2"
                    placeholder="Write your post content..."
                ></textarea>
            </div>

            <div>
                <label class="block mb-1 font-medium">Image</label>
                <input type="file" @change="handleImage" />
            </div>

            <div>
                <label class="block mb-1 font-medium">Visibility</label>
                <select
                    v-model="form.visibility"
                    class="w-full border rounded p-2"
                >
                    <option value="public">Public</option>
                    <option value="private">Private</option>
                </select>
            </div>

            <div>
                <label class="block mb-1 font-medium">Tags</label>
                <div class="flex flex-wrap gap-2 mb-2">
                    <label
                        v-for="tag in props.tags"
                        :key="tag.id"
                        class="flex items-center gap-1"
                    >
                        <input
                            type="checkbox"
                            :value="tag.name"
                            v-model="form.tags"
                        />
                        {{ tag.name }}
                    </label>
                </div>

                <label class="block mb-1"
                    >Or add new tags (comma separated)</label
                >
                <input
                    v-model="form.newTags"
                    type="text"
                    class="w-full border rounded p-2"
                    placeholder="e.g. vue, laravel, inertia"
                />
            </div>

            <button
                type="submit"
                class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 transition"
            >
                Submit
            </button>
        </form>
    </div>
</template>
