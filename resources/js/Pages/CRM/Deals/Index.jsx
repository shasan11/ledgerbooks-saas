import React, { useMemo } from "react";
import { usePage } from "@inertiajs/react";
import ReusableCrudInertia from "@/Components/ReusableCrud";
import * as Yup from "yup";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";

const stageOptions = [
  { value: "lead", label: "Lead" },
  { value: "qualified", label: "Qualified" },
  { value: "proposal", label: "Proposal" },
  { value: "won", label: "Won" },
  { value: "lost", label: "Lost" },
];

export default function Index() {
  const { items, inactiveItems, contacts = [], users = [] } = usePage().props;

  const contactOptions = useMemo(
    () =>
      contacts.map((contact) => ({
        value: contact.id,
        label: contact.code ? `${contact.name} (${contact.code})` : contact.name,
      })),
    [contacts]
  );

  const ownerOptions = useMemo(
    () =>
      users.map((user) => ({
        value: user.id,
        label: user.name || user.email || user.id,
      })),
    [users]
  );

  return (
    <AuthenticatedLayout>
      <ReusableCrudInertia
        indexUrl="/crm/deals"
        storeUrl="/crm/deals"
        updateUrl={(id) => `/crm/deals/${id}`}
        destroyUrl={(id) => `/crm/deals/${id}`}
        bulkUrl="/crm/deals/bulk"
        title="Deals"
        columns={[
          { title: "Title", dataIndex: "title", sorter: true, field: "title" },
          { title: "Stage", dataIndex: "stage", sorter: true, field: "stage" },
          { title: "Contact ID", dataIndex: "contact_id" },
        ]}
        fields={[
          { type: "text", name: "title", label: "Title", required: true, col: 12 },
          { type: "text", name: "code", label: "Code", col: 12 },
          {
            type: "select",
            name: "contact_id",
            label: "Contact",
            required: true,
            options: contactOptions,
            col: 12,
          },
          { type: "select", name: "stage", label: "Stage", options: stageOptions, col: 12 },
          { type: "date", name: "expected_close", label: "Expected Close", col: 12 },
          { type: "number", name: "probability", label: "Probability (%)", col: 12 },
          { type: "number", name: "expected_value", label: "Expected Value", col: 12 },
          { type: "text", name: "source", label: "Source", col: 12 },
          { type: "select", name: "owner_id", label: "Owner", options: ownerOptions, col: 12 },
          { type: "textarea", name: "description", label: "Description", col: 24 },
        ]}
        validationSchema={Yup.object({
          title: Yup.string().required("Required"),
          contact_id: Yup.number().required("Required"),
        })}
        crudInitialValues={{
          title: "",
          code: "",
          contact_id: null,
          stage: "lead",
          expected_close: "",
          probability: 0,
          expected_value: 0,
          source: "",
          owner_id: null,
          description: "",
        }}
        enableInactiveDrawer={true}
        form_ui="modal"
        drawerWidth={1200}
      />
    </AuthenticatedLayout>
  );
}
