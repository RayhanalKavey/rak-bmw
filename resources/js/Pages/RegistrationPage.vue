<script setup>
import { useForm, usePage } from "@inertiajs/vue3";
import { createToaster } from "@meforma/vue-toaster";

const toaster = createToaster({ position: "top-right" });
const page = usePage();

const form = useForm({
    username: "",
    email: "",
    password: "",
    image: null,
});

const handleProfilePic = (e) => {
    form.image = e.target.files[0];
};

const submit = () => {
    if (form.username.length === 0) {
        toaster.warning("User name is required");
    } else if (form.email.length === 0) {
        toaster.warning("Email is required");
    } else if (form.password.length === 0) {
        toaster.warning("Password is required");
    } else {
        form.post("/user-registration", {
            onSuccess: () => {
                if (page.props.flash.status === true) {
                    toaster.success(page.props.flash.message);
                } else {
                    toaster.error(page.props.flash.message);
                }
            },
        });
    }
};
</script>

<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-100">
        <div class="w-full max-w-md p-8 bg-white rounded-2xl shadow-md">
            <h2 class="text-2xl font-bold text-center mb-6">Register</h2>
            <form @submit.prevent="submit" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1"
                        >Username</label
                    >
                    <input
                        v-model="form.username"
                        type="text"
                        class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"
                        placeholder="Enter your username"
                    />
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Email</label>
                    <input
                        v-model="form.email"
                        type="email"
                        class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"
                        placeholder="you@example.com"
                    />
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1"
                        >Password</label
                    >
                    <input
                        v-model="form.password"
                        type="password"
                        class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"
                        placeholder="********"
                    />
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1"
                        >Profile Picture (optional)</label
                    >
                    <input
                        type="file"
                        @change="handleProfilePic"
                        class="w-full px-2 py-1 border rounded-md"
                    />
                </div>

                <button
                    type="submit"
                    class="w-full bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700 transition"
                >
                    Register
                </button>
            </form>
        </div>
    </div>
</template>

<style scoped></style>
