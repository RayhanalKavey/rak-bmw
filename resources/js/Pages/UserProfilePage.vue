<script setup>
import { usePage } from "@inertiajs/vue3";
import { ref } from "vue";
import EditProfileForm from "../Components/User/EditProfileForm.vue";

const page = usePage();
const user = ref(page.props.user);

const handleUserUpdate = (updatedUser) => {
    user.value = updatedUser;
};
</script>

<template>
    <div class="max-w-2xl mx-auto p-6 bg-white rounded-xl shadow">
        <h1 class="text-2xl font-bold mb-4">My Profile</h1>

        <div class="flex items-center space-x-4">
            <img
                :src="user.image ?? 'profilePic/default-user.webp'"
                alt="Profile"
                class="w-20 h-20 rounded-full border"
            />
            <div>
                <p><strong>Username:</strong> {{ user.username }}</p>
                <p><strong>Email:</strong> {{ user.email }}</p>
            </div>
        </div>

        <hr class="my-6" />

        <EditProfileForm :user="user" @updateUser="handleUserUpdate" />
    </div>
</template>
