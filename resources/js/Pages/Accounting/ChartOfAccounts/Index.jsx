import React, { useMemo } from "react";
import { usePage } from "@inertiajs/react";
import ReusableCrudInertia from "@/Components/ReusableCrud";
import * as Yup from "yup";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";

export default function Index() {
  const { items, inactiveItems, accountTypes = [], chartOfAccounts = [] } = usePage().props;

  const accountTypeOptions = useMemo(
    () =>
      accountTypes.map((type) => ({
        value: type.id,
        label: type.name,
      })),
    [accountTypes]
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
          {
            type: "select",
            name: "account_type_id",
            label: "Account Type",
            required: true,
            options: accountTypeOptions,
            col: 12,
          },
          {
            type: "select",
            name: "parent_id",
            label: "Parent Account",
            options: chartOptions,
            col: 12,
          },
          { type: "select", name: "c_o_a_id", label: "COA", options: chartIdOptions, col: 12 },
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
          account_type_id: null,
          parent_id: "",
          c_o_a_id: null,
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
