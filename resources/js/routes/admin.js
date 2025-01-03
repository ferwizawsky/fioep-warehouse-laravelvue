export default {
    path: "/admin",
    name: "Admin",
    component: () => import("@/layouts/admin.vue"),
    redirect: "/admin/dashboard",
    children: [
        {
            path: "dashboard",
            name: "Admin Dashboard",
            component: () => import("@/pages/admin/dashboard.vue"),
        },
        {
            path: "invoice",
            name: "Admin invoice",
            component: () => import("@/pages/admin/invoice/index.vue"),
        },
        {
            path: "invoice/:name/:id",
            name: "Admin invoice Detail",
            component: () => import("@/pages/admin/invoice/id.vue"),
        },
        {
            path: "invoice/:name",
            name: "Admin invoice Action",
            component: () => import("@/pages/admin/invoice/id.vue"),
        },

        {
            path: "user-management",
            name: "Admin User-management",
            component: () => import("@/pages/admin/user-management/index.vue"),
        },
        {
            path: "user-management/:name/:id",
            name: "Admin User-management Detail",
            component: () => import("@/pages/admin/user-management/id.vue"),
        },
        {
            path: "user-management/:name",
            name: "Admin User-management Action",
            component: () => import("@/pages/admin/user-management/id.vue"),
        },

        {
            path: "master",
            name: "Admin Master",
            component: () => import("@/pages/admin/master/index.vue"),
        },
        {
            path: "master/:name/:id",
            name: "Admin Master Detail",
            component: () => import("@/pages/admin/master/id.vue"),
        },
        {
            path: "master/:name",
            name: "Admin Master Action",
            component: () => import("@/pages/admin/master/id.vue"),
        },

        {
            path: "purchase",
            name: "Admin Purchase",
            component: () => import("@/pages/admin/purchase/index.vue"),
        },
        {
            path: "purchase/:name/:id",
            name: "Admin Purchase Detail",
            component: () => import("@/pages/admin/purchase/id.vue"),
        },
        {
            path: "purchase/:name",
            name: "Admin Purchase Action",
            component: () => import("@/pages/admin/purchase/id.vue"),
        },

        {
            path: "warehouse",
            name: "Admin Warehouse",
            component: () => import("@/pages/admin/warehouse/index.vue"),
        },
        {
            path: "warehouse/:name/:id",
            name: "Admin Warehouse Detail",
            component: () => import("@/pages/admin/warehouse/id.vue"),
        },
        {
            path: "warehouse/:name",
            name: "Admin Warehouse Action",
            component: () => import("@/pages/admin/warehouse/id.vue"),
        },

        {
            path: "storage",
            name: "Admin Storage",
            component: () => import("@/pages/admin/storage/index.vue"),
        },
    ],
};
