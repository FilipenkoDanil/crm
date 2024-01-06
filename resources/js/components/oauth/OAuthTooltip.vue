<script>
export default {
    name: "OAuthTooltip",

    emits: ['auth'],

    methods: {
        handleClick(provider) {
            this.$emit('auth', provider);
        }
    }
}
</script>

<template>
    <v-tooltip text="Vkontakte">
        <template v-slot:activator="{ props }">
            <v-btn v-bind="props" @click="handleClick('vkontakte')" variant="plain" icon="mdi-vimeo"
                   color="blue"></v-btn>
        </template>
    </v-tooltip>

    <v-tooltip text="Discord">
        <template v-slot:activator="{ props }">
            <v-btn v-bind="props" @click="handleClick('discord')" variant="text"
                   icon="mdi-microsoft-xbox-controller"
                   color="blue"></v-btn>
        </template>
    </v-tooltip>

    <v-tooltip text="Github">
        <template v-slot:activator="{ props }">
            <v-btn v-bind="props" @click="handleClick('github')" variant="text" icon="mdi-github"></v-btn>
        </template>
    </v-tooltip>

</template>

<style scoped>

</style>
