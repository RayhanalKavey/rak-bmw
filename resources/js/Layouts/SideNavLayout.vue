<script>
import { Link } from "@inertiajs/vue3";
import { ref, onMounted, watch } from "vue";

export default {
    components: {
        Link,
    },
    setup() {
        const sidebarOpen = ref(true);
        const isMobileSidebarOpen = ref(false);
        const isMobile = ref(false);

        const checkIfMobile = () => {
            isMobile.value = window.innerWidth < 1024; // lg breakpoint
        };

        const toggleSidebar = () => {
            if (isMobile.value) {
                isMobileSidebarOpen.value = !isMobileSidebarOpen.value;
            } else {
                sidebarOpen.value = !sidebarOpen.value;
            }
        };

        onMounted(() => {
            checkIfMobile();
            window.addEventListener("resize", checkIfMobile);

            // On mobile, sidebar should be closed by default
            if (isMobile.value) {
                sidebarOpen.value = false;
            }
        });

        // Watch for mobile state changes
        watch(isMobile, (newVal) => {
            if (newVal) {
                sidebarOpen.value = false;
            } else {
                isMobileSidebarOpen.value = false;
            }
        });

        return {
            sidebarOpen,
            isMobileSidebarOpen,
            toggleSidebar,
        };
    },
};
</script>

<template>
    <div class="flex h-screen bg-gray-100">
        <!-- Sidebar Backdrop (mobile only) -->
        <div
            v-if="isMobileSidebarOpen"
            class="fixed inset-0 z-20 bg-black opacity-50 lg:hidden"
            @click="isMobileSidebarOpen = false"
        ></div>

        <!-- Sidebar -->
        <aside
            class="fixed inset-y-0 left-0 z-30 w-64 overflow-y-auto bg-indigo-700 text-white transition-all duration-300 ease-in-out lg:static lg:left-0"
            :class="{
                '-translate-x-full lg:translate-x-0 lg:w-20': !sidebarOpen,
                'translate-x-0': sidebarOpen || isMobileSidebarOpen,
            }"
        >
            <div class="flex items-center justify-between p-4">
                <span
                    class="text-xl font-semibold whitespace-nowrap"
                    :class="{ 'lg:hidden': !sidebarOpen }"
                    >RAK-bmw</span
                >
                <button
                    @click="toggleSidebar"
                    class="py-1 px-3 rounded-lg hover:bg-indigo-600"
                    :class="{ 'ml-auto': !sidebarOpen }"
                >
                    <i
                        class="fa"
                        :class="{
                            'fa-times': sidebarOpen || isMobileSidebarOpen,
                            'fa-bars': !sidebarOpen && !isMobileSidebarOpen,
                        }"
                    ></i>
                </button>
            </div>

            <nav class="mt-4">
                <div class="px-4">
                    <Link
                        href="/dashboard"
                        class="flex items-center p-3 rounded-lg hover:bg-indigo-600 cursor-pointer"
                        :class="{ 'bg-indigo-800': $page.url === '/dashboard' }"
                    >
                        <i class="fa fa-home"></i>
                        <span
                            class="ml-3 whitespace-nowrap"
                            :class="{ 'lg:hidden': !sidebarOpen }"
                            >Dashboard</span
                        >
                    </Link>
                    <Link
                        href="/my-posts"
                        class="flex items-center p-3 rounded-lg hover:bg-indigo-600 cursor-pointer"
                        :class="{ 'bg-indigo-800': $page.url === '/my-posts' }"
                    >
                        <i class="fa fa-file-text"></i>

                        <span
                            class="ml-3 whitespace-nowrap"
                            :class="{ 'lg:hidden': !sidebarOpen }"
                            >Posts</span
                        >
                    </Link>
                </div>
            </nav>
        </aside>

        <!-- Main content -->
        <div class="flex-1 overflow-auto">
            <!-- Mobile header -->
            <header
                class="flex items-center justify-between p-4 bg-white border-b lg:hidden"
            >
                <button
                    @click="isMobileSidebarOpen = true"
                    class="p-2 rounded-lg"
                >
                    <i class="fa fa-bars"></i>
                </button>
                <h1 class="text-xl font-semibold">Dashboard</h1>
                <div class="w-6"></div>
                <!-- Spacer for alignment -->
            </header>

            <!-- Content -->
            <main class="p-6">
                <slot></slot>
            </main>
        </div>
    </div>
</template>

<style>
/* Add Font Awesome CSS in your project or import it in your main file */
@import url("https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css");

.fa {
    width: 24px;
    text-align: center;
}
</style>
