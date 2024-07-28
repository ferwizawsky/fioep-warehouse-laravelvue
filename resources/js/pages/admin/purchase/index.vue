<script setup>
import {
    Table,
    TableBody,
    TableCaption,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from "@/components/ui/table";
import Paginate from "@/components/inkia/table/Paginate.vue";
import {
    AlertDialog,
    AlertDialogAction,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
    AlertDialogTrigger,
} from "@/components/ui/alert-dialog";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";

import {
    FileText,
    FilePenLine,
    Trash2,
    Search,
    Printer,
    Check,
    X,
    Bus,
} from "lucide-vue-next";
import { onMounted, ref } from "vue";
import { useRoute } from "vue-router";
import { useRouter } from "vue-router";
import FunctionButton from "@/components/inkia/table/FunctionButton.vue";
import { useBlobFetch, useMyFetch } from "@/composables/fetch";
import { useNotif } from "@/stores/notif";
import { dateFormatter } from "@/composables/timeFormatter";
import { useOption } from "@/stores/option";

const option = useOption();
const notif = useNotif();
const router = useRouter();
const route = useRoute();
const page = ref(1);
const search = ref("");
const listData = ref([]);
const selectedItem = ref([]);
const limitPaginate = ref(10);
const meta = ref({});

onMounted(() => {
    if (route.query?.page) page.value = route.query?.page;
    getData();
});

async function getData() {
    listData.value = [];
    try {
        const { data } = await useMyFetch(
            "GET",
            `/purchase?search=${search.value}&page=${page.value}&limit=${limitPaginate.value}`
        );
        listData.value = [...data.data];
        meta.value = { ...data.meta };
    } catch (e) {}
}

function getStatus(e) {
    if (e === "completed") {
        return "text-primary";
    }
    if (e === "processed") {
        return "text-lime-500";
    }
    if (e === "approved") {
        return "text-violet-500";
    }
    if (e === "pending") {
        return "text-amber-500";
    }
    return "text-rose-500";
}

async function setStatus(e, value, item) {
    try {
        const { data } = await useMyFetch("POST", `/purchase/${item.id}/${e}`, {
            status: value,
        });
        getData();
        notif.make(`Success Update data`);
    } catch (error) {
    } finally {
    }
}

async function deleteData(e) {
    try {
        const { data } = await useMyFetch("POST", `/purchase/${e.id}/delete`);
        getData();
        notif.make(`Success Update data`);
    } catch (error) {
    } finally {
    }
}

async function exportExcel() {
    try {
        const { data } = await useBlobFetch("GET", `/purchase-export`);

        // Get the current date
        let named = "purchase-export";
        const today = new Date();
        const formattedDate = today.toISOString().split("T")[0]; // Format the date as YYYY-MM-DD
        const filename = `${named}_${formattedDate}.xlsx`;

        // Create a link element
        const url = window.URL.createObjectURL(new Blob([data]));
        const link = document.createElement("a");
        link.href = url;
        link.setAttribute("download", filename);

        // Append the link to the body
        document.body.appendChild(link);

        // Simulate a click to trigger the download
        link.click();

        // Remove the link from the document
        document.body.removeChild(link);

        console.log(`File downloaded successfully: ${filename}`);
    } catch (error) {
        console.error("Failed to download the file:", error);
    }
}

function setPage(e) {
    router.push(`${route.path}?page=${e}`);
    page.value = e;
    getData();
}
</script>
<template>
    <div class="pt-4">
        <div class="lg:flex items-center justify-between mb-6">
            <div class="space-x-4">
                <RouterLink :to="`${route.path}/add`">
                    <Button variant="outline">Create</Button>
                </RouterLink>
            </div>
            <div class="flex items-center space-x-4">
                <Button variant="outline" @click="exportExcel()"
                    >Export Excel</Button
                >
                <form class="relative" @submit.prevent="getData()">
                    <Input
                        v-model="search"
                        class="pr-8"
                        placeholder="Search...."
                    />
                    <Search
                        class="w-4 absolute top-2 right-3 cursor-pointer text-foreground/50"
                    />
                </form>
            </div>
        </div>
        <Table>
            <TableHeader>
                <TableRow>
                    <TableHead class=""> No. </TableHead>
                    <TableHead class=""> ID Purchase</TableHead>
                    <TableHead>Requester</TableHead>
                    <TableHead>Status</TableHead>
                    <TableHead> Responded By</TableHead>
                    <TableHead> Responded At </TableHead>
                    <TableHead
                        v-if="
                            option.roleId == 5 ||
                            option.roleId == 1 ||
                            option.roleId == 2
                        "
                    >
                        Approval
                    </TableHead>
                    <TableHead />
                    <TableHead v-if="option.roleId == 3 || option.roleId == 5">
                        Process
                    </TableHead>
                    <TableHead> Created At </TableHead>
                    <TableHead> Action </TableHead>
                </TableRow>
            </TableHeader>
            <TableBody>
                <TableRow v-for="(item, index) in listData">
                    <TableCell>{{ (page - 1) * 10 + index + 1 }}</TableCell>
                    <TableCell class="font-medium">
                        {{ item.id }}
                    </TableCell>
                    <TableCell>{{ item?.user?.name }}</TableCell>
                    <TableCell
                        :class="getStatus(item.status)"
                        class="uppercase"
                        >{{ item.status }}</TableCell
                    >
                    <TableCell class="capitalize">
                        {{ item.approved_by?.name }}
                    </TableCell>
                    <TableCell class="capitalize">
                        {{
                            item.approved_at
                                ? dateFormatter(item.approved_at)
                                : null
                        }}
                    </TableCell>
                    <TableCell
                        v-if="
                            option.roleId == 5 ||
                            option.roleId == 1 ||
                            option.roleId == 2
                        "
                        class="space-x-2 flex"
                    >
                        <FunctionButton
                            v-if="item.status == 'pending'"
                            class="bg-primary/20 hover:bg-primary/50 text-primary"
                            @click="setStatus('approval', 'approved', item)"
                        >
                            <Check class="w-4 h-4" />
                        </FunctionButton>

                        <FunctionButton
                            v-if="item.status == 'pending'"
                            class="bg-rose-600/20 hover:bg-rose-600/50 text-rose-600"
                            @click="setStatus('approval', 'rejected', item)"
                        >
                            <X class="w-4 h-4" />
                        </FunctionButton>
                    </TableCell>
                    <TableCell></TableCell>
                    <TableCell
                        v-if="option.roleId == 5 || option.roleId == 3"
                        class="space-x-2 flex"
                    >
                        <FunctionButton
                            v-if="item.status == 'approved'"
                            class="bg-primary/20 hover:bg-primary/50 text-primary"
                            @click="setStatus('process', '', item)"
                        >
                            <Bus class="w-4 h-4" />
                        </FunctionButton>
                    </TableCell>
                    <TableCell class="capitalize">
                        {{ dateFormatter(item.created_at) }}
                    </TableCell>
                    <TableCell class="flex items-center space-x-2">
                        <RouterLink :to="`${route.path}/detail/${item?.id}`">
                            <FunctionButton
                                class="bg-primary/20 hover:bg-primary/50 text-primary"
                            >
                                <FileText class="w-4 h-4" />
                            </FunctionButton>
                        </RouterLink>
                        <!-- <RouterLink :to="`${route.path}/edit/${item?.id}`">
                            <FunctionButton
                                class="bg-primary/20 hover:bg-primary/50 text-primary"
                            >
                                <FilePenLine class="w-4 h-4" />
                            </FunctionButton>
                        </RouterLink> -->
                        <AlertDialog>
                            <AlertDialogTrigger as-child>
                                <FunctionButton
                                    class="bg-rose-600/20 hover:bg-rose-600/50 text-rose-600"
                                >
                                    <Trash2 class="w-4 h-4" />
                                </FunctionButton>
                            </AlertDialogTrigger>
                            <AlertDialogContent>
                                <AlertDialogHeader>
                                    <AlertDialogTitle
                                        >Apakah Anda Yakin?</AlertDialogTitle
                                    >
                                    <AlertDialogDescription>
                                        Data setelah dihapus tidak bisa
                                        dikembalikan lagi dari server!
                                    </AlertDialogDescription>
                                </AlertDialogHeader>
                                <AlertDialogFooter>
                                    <AlertDialogCancel>
                                        Batal
                                    </AlertDialogCancel>
                                    <AlertDialogAction
                                        @click="deleteData(item)"
                                    >
                                        Lanjut
                                    </AlertDialogAction>
                                </AlertDialogFooter>
                            </AlertDialogContent>
                        </AlertDialog>
                    </TableCell>
                </TableRow>
            </TableBody>
        </Table>

        <Paginate
            @move="setPage($event)"
            :page="page"
            :list="meta?.links"
            :meta="meta"
            :limit-paginate="limitPaginate"
            @update="limitPaginate = $event"
        />
    </div>
</template>
