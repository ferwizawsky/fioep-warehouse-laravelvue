import { defineStore } from "pinia";
import { ref } from "vue";
import { useMyFetch } from "@/composables/fetch";

export const useMaster = defineStore("master", () => {
    const listMaster = ref({
        suppliers: [],
        materials: [],
        storages: [],
    });
    const limit = 10000000000;

    async function getMaterial() {
        try {
            const { data } = await useMyFetch(
                "GET",
                `/material?limit=${limit}`
            );
            listMaster.value.materials = data?.data ?? [];
        } catch (e) {}
    }

    async function getStorage() {
        try {
            const { data } = await useMyFetch("GET", `/storage?limit=${limit}`);
            listMaster.value.storages = data?.data ?? [];
        } catch (e) {}
    }

    async function getSupplier() {
        try {
            const { data } = await useMyFetch(
                "GET",
                `/supplier?limit=${limit}`
            );
            listMaster.value.suppliers = data?.data ?? [];
        } catch (e) {}
    }

    const getData = () => {
        getSupplier();
        getMaterial();
        getStorage();
    };
    return {
        listMaster,
        getData,
        getMaterial,
        getStorage,
        getSupplier,
    };
});
