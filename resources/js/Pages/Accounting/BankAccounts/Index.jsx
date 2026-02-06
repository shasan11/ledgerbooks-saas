import React, { useMemo } from "react";
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
  const { items, inactiveItems, currencies = [], chartOfAccounts = [] } = usePage().props;

  const currencyOptions = useMemo(
    () =>
      currencies.map((currency) => ({
        value: currency.id,
        label: `${currency.code} - ${currency.name}`,
      })),
    [currencies]
  );

  const chartOptions = useMemo(
    () =>
      chartOfAccounts.map((account) => ({
        value: account.id,
        label: account.code ? `${account.code} - ${account.name}` : account.name,
      })),
    [chartOfAccounts]
  );

  const chartIdOptions = useMemo(
    () =>
      chartOfAccounts.map((account) => ({
        value: account.c_o_a_id ?? account.id,
        label: account.code ? `${account.code} - ${account.name}` : account.name,
      })),
    [chartOfAccounts]
  );

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
          { type: "select", name: "currency_id", label: "Currency", options: currencyOptions, col: 12 },
          { type: "select", name: "coa_account_id", label: "COA Account", options: chartOptions, col: 12 },
          { type: "select", name: "c_o_a_id", label: "COA", options: chartIdOptions, col: 12 },
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
          currency_id: null,
          coa_account_id: null,
          c_o_a_id: null,
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
