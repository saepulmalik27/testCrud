<template>
  <div class="card">
    <div v-if="title" class="card-header">
     <h3>{{ title }}</h3> 
    </div>

    <div class="card-body">
      <slot />
    </div>
  </div>
</template>

<script>
export default {
  name: 'Card',

  props: {
    title: { type: String, default: null }
  }
}
</script>
