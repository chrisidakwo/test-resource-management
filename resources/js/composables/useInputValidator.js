import { ref, watch } from "vue";

export function useInputValidator(name, startVal, validators, onValidate) {
    const input = ref(startVal);
    const errors = ref([]);

    watch(input, (value) => {
        errors.value = validators.map(validator => validator(value, name));
        onValidate(value);
    });

    return {
        input,
        errors
    }
}
