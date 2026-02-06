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

const CODE_PREFIXES = {
  bank: "BA",
  cash: "BC",
};

const CODE_PADDING = 5;

const buildNextCode = (type, rows) => {
  const prefix = CODE_PREFIXES[type];
  if (!prefix) return "";

  const matcher = new RegExp(`^${prefix}(\\d+)$`, "i");
  const maxValue = (rows || []).reduce((max, row) => {
    const code = row?.code || "";
    const match = code.match(matcher);
    if (!match) return max;
    const value = Number(match[1]);
    if (!Number.isFinite(value)) return max;
    return Math.max(max, value);
  }, 0);

  return `${prefix}${String(maxValue + 1).padStart(CODE_PADDING, "0")}`;
};

export default function Index() {
  const { items, inactiveItems, currencies = [] } = usePage().props;

  const allRows = useMemo(
    () => [...(items?.data || []), ...(inactiveItems?.data || [])],
    [items?.data, inactiveItems?.data]
  );

  const currencyOptions = useMemo(
    () =>
      currencies.map((currency) => ({
        value: currency.id,
        label: `${currency.code} - ${currency.name}`,
      })),
    [currencies]
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
        ]}
        fields={[
          { type: "select", name: "type", label: "Type", options: typeOptions, required: true, col: 12 },
          { type: "text", name: "display_name", label: "Display Name", required: true, col: 12 },
          {
            type: "text",
            name: "bank_name",
            label: "Bank Name",
            col: 12,
            condition: (values) => values?.type === "bank",
          },
          {
            type: "text",
            name: "code",
            label: "Code",
            col: 12,
            readOnly: true,
            condition: (values) => values?.type === "bank",
            formula: (values) =>
              values?.code || buildNextCode(values?.type, allRows),
          },
          {
            type: "text",
            name: "account_name",
            label: "Account Name",
            col: 12,
            condition: (values) => values?.type === "bank",
          },
          {
            type: "text",
            name: "account_number",
            label: "Account Number",
            col: 12,
            condition: (values) => values?.type === "bank",
          },
          {
            type: "select",
            name: "account_type",
            label: "Account Type",
            options: accountTypeOptions,
            col: 12,
            condition: (values) => values?.type === "bank",
          },
          { type: "select", name: "currency_id", label: "Currency", options: currencyOptions, col: 12 },
          { type: "textarea", name: "description", label: "Description", col: 24 },
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
        }}
        enableInactiveDrawer={true}
        form_ui="modal"
        drawerWidth={1200}
      />
    </AuthenticatedLayout>
  );
}
