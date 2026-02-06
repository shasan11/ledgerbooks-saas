import React from "react";
import { usePage } from "@inertiajs/react";
import ReusableCrudInertia from "@/Components/ReusableCrud";
import * as Yup from "yup";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";

export default function Index() {
  const { items, inactiveItems } = usePage().props;

  return (
    <AuthenticatedLayout>
      <ReusableCrudInertia
        indexUrl="/accounting/chart-of-accounts"
        storeUrl="/accounting/chart-of-accounts"
        updateUrl={(id) => `/accounting/chart-of-accounts/${id}`}
        destroyUrl={(id) => `/accounting/chart-of-accounts/${id}`}
        bulkUrl="/accounting/chart-of-accounts/bulk"
        title="Chart of Accounts"
        columns={[
          { title: "Name", dataIndex: "name", sorter: true, field: "name" },
          { title: "Code", dataIndex: "code", sorter: true, field: "code" },
          { title: "Active", dataIndex: "active", render: (v) => (v ? "Yes" : "No") },
        ]}
        fields={[
          { type: "text", name: "name", label: "Name", required: true, col: 12 },
          { type: "text", name: "code", label: "Code", required: true, col: 12 },
          { type: "number", name: "account_type_id", label: "Account Type ID", required: true, col: 12 },
          { type: "text", name: "parent_id", label: "Parent ID", col: 12 },
          { type: "number", name: "c_o_a_id", label: "COA ID", col: 12 },
          { type: "checkbox", name: "is_group", label: "Is Group", col: 12 },
          { type: "checkbox", name: "is_system", label: "Is System", col: 12 },
          { type: "textarea", name: "description", label: "Description", col: 24 },
          { type: "switch", name: "active", label: "Active", col: 24 },
        ]}
        validationSchema={Yup.object({
          name: Yup.string().required("Required"),
          code: Yup.string().required("Required"),
          account_type_id: Yup.number().required("Required"),
        })}
        crudInitialValues={{
          name: "",
          code: "",
          account_type_id: "",
          parent_id: "",
          c_o_a_id: 0,
          is_group: false,
          is_system: false,
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
