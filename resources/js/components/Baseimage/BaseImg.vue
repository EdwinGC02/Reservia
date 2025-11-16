<template>
  <img
    :src="imageUrl"
    :alt="alt"
    :class="imgClass"
    :height="height"
    :width="width"
    :id="id"
    v-bind="$attrs"
    :style="style"
  />
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  src: { type: String, default: '' },
  alt: { type: String, default: '' },
  imgClass: { type: String, default: '' },
  id: { type: String, default: '' },
  height: { type: [String, Number], default: null },
  width: { type: [String, Number], default: null },
  style: { type: String, default: '' }
})

const isExternalUrl = computed(() => /^https?:\/\//.test(props.src))

const imageUrl = computed(() => {
  const basePathValue = !isExternalUrl.value ? __BASE_PATH__ : ''
  return `${basePathValue}${props.src}`
})
</script>
