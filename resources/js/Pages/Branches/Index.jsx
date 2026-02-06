import React, { useMemo } from "react";
import { usePage } from "@inertiajs/react";
import ReusableCrudInertia from "@/Components/ReusableCrud";
import * as Yup from "yup";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";

export default function BranchesIndex() {
  const { meta } = usePage().props;

  const currencyOptions = useMemo(
    () =>
      (meta?.currencies || []).map((currency) => ({
        value: currency.id,
        label: `${currency.code} - ${currency.name}`,
      })),
    [meta?.currencies]
  );

  const columns = useMemo(
    () => [
      { title: "Name", dataIndex: "name", sorter: true, field: "name" },
      { title: "Code", dataIndex: "code", sorter: true, field: "code" },
      { title: "Country", dataIndex: "country", sorter: true, field: "country" },
      { title: "City", dataIndex: "city", sorter: true, field: "city" },
      { title: "Active", dataIndex: "active", render: (v) => (v ? "Yes" : "No") },
      { title: "Head Office", dataIndex: "is_head_office", render: (v) => (v ? "Yes" : "No") },
    ],
    []
  );

  const fields = useMemo(
    () => [
      { type: "text", name: "code", label: "Code", col: 12 },
      { type: "text", name: "name", label: "Name", required: true, col: 12 },
      { type: "text", name: "email", label: "Email", col: 12 },
      { type: "text", name: "phone", label: "Phone", col: 12 },
      { type: "text", name: "country", label: "Country", col: 12 },
      { type: "text", name: "city", label: "City", col: 12 },
      { type: "text", name: "timezone", label: "Timezone", col: 12 },
      {
        type: "select",
        name: "currency_id",
        label: "Currency",
        options: currencyOptions,
        col: 12,
      },
      { type: "textarea", name: "address", label: "Address", col: 24 },
      { type: "switch", name: "is_head_office", label: "Head Office", col: 12 },
      { type: "switch", name: "active", label: "Active", col: 12 },
    ],
    [currencyOptions]
  );

  const validationSchema = Yup.object({
    name: Yup.string().required("Required"),
    email: Yup.string()
      .transform((value) => (value === "" ? null : value))
      .nullable()
      .email("Invalid email"),
  });

  const initialValues = {
    code: "",
    name: "",
    email: "",
    phone: "",
    address: "",
    country: "",
    city: "",
    timezone: "",
    currency_id: null,
    is_head_office: false,
    active: true,
  };

  return (
    <AuthenticatedLayout>
      <ReusableCrudInertia
        indexUrl="/branches"
        storeUrl="/branches"
        updateUrl={(id) => `/branches/${id}`}
        destroyUrl={(id) => `/branches/${id}`}
        bulkUrl="/branches/bulk"
        title="Branches"
        columns={columns}
        fields={fields}
        validationSchema={validationSchema}
        crudInitialValues={initialValues}
        searchParam="q"
        anchorFilters={[
          { key: "all", label: "All", title: "Branches", params: {} },
          { key: "head-office", label: "Head Office", title: "Branches", params: { is_head_office: 1 } },
          { key: "non-head-office", label: "Not Head Office", title: "Branches", params: { is_head_office: 0 } },
        ]}
        enableInactiveDrawer={true}
        form_ui="modal"
        drawerWidth={1200}
      />
    </AuthenticatedLayout>
  );
}
