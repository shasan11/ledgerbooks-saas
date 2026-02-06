import React from "react";
import { usePage } from "@inertiajs/react";
import ReusableCrudInertia from "@/Components/ReusableCrud";
import * as Yup from "yup";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";

const typeOptions = [
  { value: "bank", label: "Bank" },
  { value: "cash", label: "Cash" },
];

const accountTypeOptions = [
  { value: "saving", label: "Saving" },
  { value: "current", label: "Current" },
];

export default function Index() {
  const { items, inactiveItems } = usePage().props;

  return (
    <AuthenticatedLayout>
      <ReusableCrudInertia
        indexUrl="/accounting/bank-accounts"
        storeUrl="/accounting/bank-accounts"
        updateUrl={(id) => `/accounting/bank-accounts/${id}`}
        destroyUrl={(id) => `/accounting/bank-accounts/${id}`}
        bulkUrl="/accounting/bank-accounts/bulk"
        title="Bank Accounts"
        columns={[
          { title: "Display Name", dataIndex: "display_name", sorter: true, field: "display_name" },
          { title: "Type", dataIndex: "type", sorter: true, field: "type" },
          { title: "Account #", dataIndex: "account_number" },
          { title: "Active", dataIndex: "active", render: (v) => (v ? "Yes" : "No") },
        ]}
        fields={[
          { type: "select", name: "type", label: "Type", options: typeOptions, required: true, col: 12 },
          { type: "text", name: "display_name", label: "Display Name", required: true, col: 12 },
          { type: "text", name: "bank_name", label: "Bank Name", col: 12 },
          { type: "text", name: "code", label: "Code", col: 12 },
          { type: "text", name: "account_name", label: "Account Name", col: 12 },
          { type: "text", name: "account_number", label: "Account Number", col: 12 },
          { type: "select", name: "account_type", label: "Account Type", options: accountTypeOptions, col: 12 },
          { type: "number", name: "currency_id", label: "Currency ID", col: 12 },
          { type: "text", name: "coa_account_id", label: "COA Account ID", col: 12 },
          { type: "number", name: "c_o_a_id", label: "COA ID", col: 12 },
          { type: "textarea", name: "description", label: "Description", col: 24 },
          { type: "switch", name: "active", label: "Active", col: 24 },
        ]}
        validationSchema={Yup.object({
          type: Yup.string().required("Required"),
          display_name: Yup.string().required("Required"),
        })}
        crudInitialValues={{
          type: "bank",
          display_name: "",
          bank_name: "",
          code: "",
          account_name: "",
          account_number: "",
          account_type: "",
          currency_id: "",
          coa_account_id: "",
          c_o_a_id: 0,
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
