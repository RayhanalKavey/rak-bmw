<script setup>
import { useForm, usePage } from "@inertiajs/vue3";
import { createToaster } from "@meforma/vue-toaster";
import ImageUpload from "../Components/ImageProcessing/ImageUpload.vue";

const page = usePage();
const toaster = createToaster();

const form = useForm({
    title: page.props.post.title,
    content: page.props.post.content,
    visibility: page.props.post.visibility,
    image: page.props.post.image,
    tags: page.props.tags?.map((tag) => tag.name) || [],
    newTags: "",
});

const handleSubmit = () => {
    // Process tags
    if (form.newTags) {
        form.tags = [
            ...form.tags,
            ...form.newTags
                .split(",")
                .map((tag) => tag.trim())
                .filter((tag) => tag.length),
        ];
    }
    if (!(form.image instanceof File)) {
        form.image = null;
    }

    form.post(`/post-update/${page.props.post.id}`, {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: ({ props }) => {
            if (props.flash.status === true) {
                toaster.success(props.flash.message);
                // emit("updateUser", props.user);
            } else {
                toaster.error(props.flash.message);
            }
        },
    });
};
</script>

<template>
    <div class="max-w-xl mx-auto bg-white p-6 rounded-xl shadow">
        <h1 class="text-2xl font-bold mb-4">Edit Post</h1>

        <form @submit.prevent="handleSubmit" class="space-y-4">
            <!-- Title -->
            <div>
                <label class="block mb-1 font-medium">Title</label>
                <input
                    v-model="form.title"
                    type="text"
                    class="w-full border rounded p-2"
                    placeholder="Post title"
                    required
                />
            </div>

            <!-- Content -->
            <div>
                <label class="block mb-1 font-medium">Content</label>
                <textarea
                    v-model="form.content"
                    class="w-full border rounded p-2"
                    rows="5"
                    placeholder="Post content..."
                    required
                ></textarea>
            </div>

            <!-- Image Upload -->
            <div>
                <label class="block mb-1 text-lg font-semibold"
                    >Post Image</label
                >
                <ImageUpload
                    :productImage="form.image"
                    :placeholderImage="'postsImage/default-post-image.jpg'"
                    @image="(e) => (form.image = e)"
                />
            </div>

            <!-- Visibility -->
            <div>
                <label class="block mb-1 font-medium">Visibility</label>
                <select
                    v-model="form.visibility"
                    class="w-full border rounded p-2"
                    required
                >
                    <option value="public">Public</option>
                    <option value="private">Private</option>
                </select>
            </div>

            <!-- Tags -->
            <div>
                <label class="block mb-1 font-medium">Tags</label>
                <div class="flex flex-wrap gap-2 mb-2">
                    <label
                        v-for="tag in tags"
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

                <label class="block mb-1">Add new tags (comma separated)</label>
                <input
                    v-model="form.newTags"
                    type="text"
                    class="w-full border rounded p-2"
                    placeholder="e.g. vue, inertia, blog"
                />
            </div>

            <button
                type="submit"
                class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 transition disabled:opacity-50"
                :disabled="form.processing"
            >
                Update Post
            </button>
        </form>
    </div>
</template>
