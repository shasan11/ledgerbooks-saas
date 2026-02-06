import React from "react";
import { usePage } from "@inertiajs/react";
import ReusableCrudInertia from "@/Components/ReusableCrud";
import * as Yup from "yup";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";

export default function Index() {
  const { items, inactiveItems, query } = usePage().props;

  return (
    <AuthenticatedLayout>
      <ReusableCrudInertia
        indexUrl="/crm/contact-groups"
        storeUrl="/crm/contact-groups"
        updateUrl={(id) => `/crm/contact-groups/${id}`}
        destroyUrl={(id) => `/crm/contact-groups/${id}`}
        bulkUrl="/crm/contact-groups/bulk"
        title="Contact Groups"
        columns={[
          { title: "Name", dataIndex: "name", sorter: true, field: "name" },
          { title: "Active", dataIndex: "active", render: (v) => (v ? "Yes" : "No") },
        ]}
        fields={[
          { type: "text", name: "name", label: "Name", required: true, col: 24 },
          { type: "text", name: "parent_id", label: "Parent ID", col: 12 },
          { type: "number", name: "contact_group_id", label: "Contact Group ID", col: 12 },
          { type: "textarea", name: "description", label: "Description", col: 24 },
          { type: "switch", name: "active", label: "Active", col: 24 },
        ]}
        validationSchema={Yup.object({
          name: Yup.string().required("Required"),
        })}
        crudInitialValues={{
          name: "",
          parent_id: "",
          contact_group_id: 0,
          description: "",
          active: true,
        }}
        enableInactiveDrawer={true}
        form_ui="modal"
        drawerWidth={1200}
      />
    </AuthenticatedLayout>
  );
}
