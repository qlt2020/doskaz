<template>
    <div class="form-group row" :class="{error: error}">
        <label class="col-sm-3 col-form-label">{{label}} <span class="text-danger" v-if="required">*</span></label>
        <div class="col-sm-9">
            <slot v-bind:error="error"/>
            <div class="invalid-feedback" v-if="error" style="display: block">
                {{error}}
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "FieldWrapper",
        props: [
            'error',
            'label',
            'required'
        ]
    }
</script>

<style scoped>

</style>
