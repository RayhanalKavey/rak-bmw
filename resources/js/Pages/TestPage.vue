<script setup>
import { Link } from "@inertiajs/vue3";
import SideNavLayout from "../Layouts/SideNavLayout.vue";
</script>

<template>
    <SideNavLayout>
        Test page Link

        <Link href="/user-logout">
            <p>Log out</p>
        </Link>
    </SideNavLayout>
</template>

<style scoped></style>
