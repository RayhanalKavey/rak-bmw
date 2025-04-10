<template>
    <div>
        <label for="image">
            <img
                :src="(preview || currentImage) ?? props.placeholderImage"
                class="img-thumbnail"
                height="50px"
                width="50px"
            />
        </label>
        <input
            @input="imageSelected($event)"
            type="file"
            name="image"
            id="image"
        />
    </div>
</template>

<script setup>
import { ref } from "vue";
const props = defineProps({
    productImage: String,
    placeholderImage: String,
});

const currentImage = props.productImage
    ? props.productImage
    : "placeholder.png";
const preview = ref(currentImage);

const emit = defineEmits(["image"]);

const imageSelected = (e) => {
    const file = e.target.files[0];
    if (file) {
        preview.value = URL.createObjectURL(file);
        emit("image", file);
    }
};
</script>

<!-- <template>
    <div>
        <label for="image">
            <img
                :src="currentImage"
                class="img-thumbnail"
                :height="props.size"
                :width="props.size"
            />
        </label>
        <input
            @input="imageSelected($event)"
            type="file"
            name="image"
            id="image"
        />
    </div>
</template>

<script setup>
import { ref, computed } from "vue";

const props = defineProps({
    contentImage: String,
    size: Number,
    placeholderImage: String,
});

const preview = ref(null);

const currentImage = computed(() => {
    return preview.value || props.contentImage || props.placeholderImage;
});

const emit = defineEmits(["image"]);

const imageSelected = (e) => {
    const file = e.target.files[0];
    if (file) {
        preview.value = URL.createObjectURL(file);
        emit("image", file);
    } else {
        emit("image", null);
    }
};
</script> -->
