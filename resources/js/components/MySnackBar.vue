<script>
export default {
    name: "MySnackBar",

    props: {
        snackbarText: {
            type: String,
        }
    }
}
</script>

<template>
    <v-snackbar
        timeout="2000"
        color="black"
    >
        <span class="text-subtitle-1">{{ snackbarText }}</span>
        <template v-slot:actions>
            <v-btn
                color="red"
                variant="text"
                @click="$emit('closeSnack')"
            >
                Close
            </v-btn>
        </template>
    </v-snackbar>
</template>

<style scoped>

</style>
