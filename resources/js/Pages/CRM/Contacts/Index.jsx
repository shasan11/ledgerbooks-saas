import React, { useMemo } from "react";
import { usePage } from "@inertiajs/react";
import ReusableCrudInertia from "@/Components/ReusableCrud";
import * as Yup from "yup";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";

const typeOptions = [
  { value: "customer", label: "Customer" },
  { value: "supplier", label: "Supplier" },
  { value: "lead", label: "Lead" },
];

export default function Index() {
  const { items, inactiveItems, type, title, contactGroups = [] } = usePage().props;
  const fixedType = Boolean(type);

  const contactGroupOptions = useMemo(
    () =>
      contactGroups.map((group) => ({
        value: group.contact_group_id ?? group.id,
        label: group.name,
      })),
    [contactGroups]
  );

  const fields = useMemo(() => {
    const base = [
      { type: "text", name: "name", label: "Name", required: true, col: 12 },
      { type: "text", name: "code", label: "Code", col: 12 },
      { type: "text", name: "phone", label: "Phone", col: 12 },
      { type: "text", name: "email", label: "Email", col: 12 },
      { type: "text", name: "pan", label: "PAN", col: 12 },
      {
        type: "select",
        name: "contact_group_id",
        label: "Contact Group",
        options: contactGroupOptions,
        col: 12,
      },
      { type: "textarea", name: "address", label: "Address", col: 24 },
      { type: "checkbox", name: "accept_purchase", label: "Accept Purchase", col: 12 },
      { type: "number", name: "credit_terms_days", label: "Credit Terms (Days)", col: 12 },
      { type: "number", name: "credit_limit", label: "Credit Limit", col: 12 },
      { type: "textarea", name: "notes", label: "Notes", col: 24 },
    ];

    if (!fixedType) {
      base.unshift({
        type: "select",
        name: "type",
        label: "Type",
        required: true,
        options: typeOptions,
        col: 12,
      });
    }

    return base;
  }, [contactGroupOptions, fixedType]);

  const columns = useMemo(() => {
    const base = [
      { title: "Name", dataIndex: "name", sorter: true, field: "name" },
      { title: "Phone", dataIndex: "phone" },
      { title: "Email", dataIndex: "email" },
    ];

    if (!fixedType) {
      base.splice(1, 0, { title: "Type", dataIndex: "type", sorter: true, field: "type" });
    }

    return base;
  }, [fixedType]);

  const initialValues = {
    type: type ?? "customer",
    name: "",
    code: "",
    phone: "",
    email: "",
    pan: "",
    contact_group_id: null,
    address: "",
    accept_purchase: false,
    credit_terms_days: 0,
    credit_limit: 0,
    notes: "",
  };

  const validationSchema = Yup.object({
    type: Yup.string().required("Required"),
    name: Yup.string().required("Required"),
    email: Yup.string()
      .transform((value) => (value === "" ? null : value))
      .nullable()
      .email("Invalid email"),
  });

  return (
    <AuthenticatedLayout>
      <ReusableCrudInertia
        indexUrl="/crm/contacts"
        storeUrl="/crm/contacts"
        updateUrl={(id) => `/crm/contacts/${id}`}
        destroyUrl={(id) => `/crm/contacts/${id}`}
        bulkUrl="/crm/contacts/bulk"
        title={title ?? "Contacts"}
        columns={columns}
        fields={fields}
        validationSchema={validationSchema}
        crudInitialValues={initialValues}
        enableInactiveDrawer={true}
        form_ui="modal"
        drawerWidth={1200}
      />
    </AuthenticatedLayout>
  );
}
