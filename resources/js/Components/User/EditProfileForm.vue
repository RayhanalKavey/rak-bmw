<script setup>
import { useForm, usePage } from "@inertiajs/vue3";
import { createToaster } from "@meforma/vue-toaster";
import ImageUpload from "../ImageProcessing/ImageUpload.vue";
import { defineEmits } from "vue";

const page = usePage();
const toaster = createToaster({ position: "top-right" });

const emit = defineEmits();

const form = useForm({
    username: page.props.user.username,
    email: page.props.user.email,
    image: page.props.user.image,
});

const submit = () => {
    if (!(form.image instanceof File)) {
        form.image = null;
    }

    form.post("/user-update", {
        forceFormData: true,
        onSuccess: ({ props }) => {
            if (props.flash.status === true) {
                toaster.success(props.flash.message);
                emit("updateUser", props.user);
            } else {
                toaster.error(props.flash.message);
            }
        },
        onError: () => toaster.error("Update failed!"),
    });
};
</script>

<template>
    <form @submit.prevent="submit" class="space-y-4">
        <div>
            <label class="block mb-1 text-lg font-semibold">Username</label>
            <input
                v-model="form.username"
                type="text"
                class="w-full p-2 border border-gray-300 rounded-md"
            />
        </div>

        <div>
            <label class="block mb-1 text-lg font-semibold">Email</label>
            <input
                v-model="form.email"
                type="email"
                class="w-full p-2 border border-gray-300 rounded-md"
            />
        </div>

        <div>
            <label class="block mb-1 text-lg font-semibold"
                >Profile Picture</label
            >
            <ImageUpload
                :productImage="form.image"
                :placeholderImage="'profilePic/default-user.webp'"
                @image="(e) => (form.image = e)"
            />
        </div>

        <button
            type="submit"
            class="w-full py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition"
        >
            Update Profile
        </button>
    </form>
</template>
