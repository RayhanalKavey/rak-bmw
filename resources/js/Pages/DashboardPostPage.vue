<script setup>
import { ref, watch } from "vue";
import { Link, usePage, router } from "@inertiajs/vue3";
import EasyDataTable from "vue3-easy-data-table";
import "vue3-easy-data-table/dist/style.css";
import { createToaster } from "@meforma/vue-toaster";
import SideNavLayout from "../Layouts/SideNavLayout.vue";

const toaster = createToaster({ position: "top-right" });
const page = usePage();

// Headers must match your data keys
const Header = [
    { text: "Image", value: "image" },
    { text: "Title", value: "title" },
    { text: "Actions", value: "action" },
];
const searchField = "title";

// Initialize with empty array fallback
const Items = ref(page.props.posts);

watch(
    () => page.props.posts,
    (newPosts) => {
        Items.value = newPosts || [];
    }
);

const searchValue = ref("");

const DeleteClick = (id) => {
    if (confirm("Are you sure you want to delete this post?")) {
        router.delete(`/delete-post/${id}`, {
            onSuccess: () => {
                toaster.success(page.props.flash.message);
            },
            preserveScroll: true,
        });
    }
};
</script>

<template>
    <SideNavLayout>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h3>Posts</h3>
                            <hr />
                            <div class="float-end">
                                <Link
                                    href="/createPostPage"
                                    class="btn btn-success mx-3 btn-sm"
                                >
                                    Add Post
                                </Link>
                            </div>
                            <div>
                                <input
                                    v-model="searchValue"
                                    placeholder="Search..."
                                    class="form-control mb-2 w-auto form-control-sm"
                                    type="text"
                                />
                                <EasyDataTable
                                    buttons-pagination
                                    alternating
                                    :headers="Header"
                                    :items="Items"
                                    :search-field="searchField"
                                    :search-value="searchValue"
                                    :rows-per-page="10"
                                    show-index
                                >
                                    <template
                                        #item-image="{ image }"
                                        class="pt-2 pb-2"
                                    >
                                        <img
                                            :src="
                                                image
                                                    ? `${image}`
                                                    : 'postsImage/default-post-image.webp'
                                            "
                                            :alt="image"
                                            alt=""
                                            height="auto"
                                            width="40px"
                                        />
                                    </template>
                                    <template #item-action="{ id }">
                                        <Link
                                            :href="`/updatePostPage/${id}`"
                                            class="btn btn-success mx-3 btn-sm"
                                        >
                                            Edit
                                        </Link>
                                        <button
                                            @click="DeleteClick(id)"
                                            class="btn btn-danger btn-sm"
                                        >
                                            Delete
                                        </button>
                                    </template>
                                    <template #empty-message>
                                        <span class="text-muted"
                                            >No posts available.</span
                                        >
                                    </template>
                                </EasyDataTable>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </SideNavLayout>
</template>
