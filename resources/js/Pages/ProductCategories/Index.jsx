import React from "react";
import { usePage } from "@inertiajs/react";
import ReusableCrudInertia from "@/Components/ReusableCrud";
import * as Yup from "yup";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";

export default function Index() {
  const { items, inactiveItems, query } = usePage().props;

  return (
    <AuthenticatedLayout header={<h2>Product cateogies</h2>}>
    <ReusableCrudInertia
      indexUrl="/product-categories"
      storeUrl="/product-categories"
      updateUrl={(id) => `/product-categories/${id}`}
      destroyUrl={(id) => `/product-categories/${id}`}
      bulkUrl="/product-categories/bulk"
      title="Product Categories"
      columns={[
        { title: "Name", dataIndex: "name", sorter: true, field: "name" },
        { title: "Active", dataIndex: "active", render: (v) => (v ? "Yes" : "No") },
      ]}
      fields={[
        { type: "text", name: "name", label: "Name", required: true, col: 24 },
        { type: "textarea", name: "description", label: "Description", col: 24 },
        { type: "switch", name: "active", label: "Active", col: 24 },
      ]}
      validationSchema={Yup.object({
        name: Yup.string().required("Required"),
      })}
      crudInitialValues={{ name: "", description: "", active: true }}
      anchorFilters={[
        { key: "all", label: "All", title: "Product Categories", params: {} },
        { key: "active", label: "Active", title: "Product Categories", params: { active: 1 } },
        { key: "inactive", label: "Inactive", title: "Product Categories", params: { active: 0 } },
      ]}
      enableInactiveDrawer={true}
      form_ui="drawer"
      drawerWidth={1200}
    />
    </AuthenticatedLayout>
  );
}
