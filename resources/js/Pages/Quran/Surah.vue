<script setup>
defineProps({
  surah: Object
});
</script>

<template>
  <div v-if="surah && surah.verses && surah.verses.length > 0">
    <div
      v-for="verse in surah.verses"
      :key="verse.id"
      class="mb-4"
    >
      <p class="text-xl font-arabic" dir="rtl">
        {{ verse.text_uthmani }}
      </p>

      <p class="text-sm text-gray-600">
        {{ verse.translations?.[0]?.text }}
      </p>
    </div>
  </div>
  <div v-else>
    <p>Loading Quran verses...</p>
  </div>
</template>
