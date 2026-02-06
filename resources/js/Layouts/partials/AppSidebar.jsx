// resources/js/Layouts/partials/AppSidebar.jsx
import React, { useMemo, useState, useEffect } from "react";
import { Layout, Menu, Input, Button, Dropdown, Row, Col, Typography, Divider } from "antd";
import { Link, router } from "@inertiajs/react";

import {
  HomeOutlined,
  TeamOutlined,
  ContactsOutlined,
  UserOutlined,
  ShopOutlined,
  ApartmentOutlined,
  IdcardOutlined,
  TrophyOutlined,
  AlertOutlined,
  CheckSquareOutlined,
  ClockCircleOutlined,
  BookOutlined,
  BankOutlined,
  FileTextOutlined,
  TransactionOutlined,
  SwapOutlined,
  InboxOutlined,
  FileDoneOutlined,
  SnippetsOutlined,
  CreditCardOutlined,
  DollarOutlined,
  WalletOutlined,
  ShoppingCartOutlined,
  ShoppingOutlined,
  TagsOutlined,
  AppstoreOutlined,
  BarcodeOutlined,
  BuildOutlined,
  DatabaseOutlined,
  BgColorsOutlined,
  DeploymentUnitOutlined,
  ReconciliationOutlined,
  FundProjectionScreenOutlined,
  BarChartOutlined,
  SettingOutlined,
  PlusOutlined,
  SearchOutlined,
} from "@ant-design/icons";

const { Sider } = Layout;
const { Text } = Typography;

/* ---------------------------------------
   Helpers
---------------------------------------- */
function cleanText(s = "") {
  return String(s).toLowerCase().trim();
}

function filterMenuTree(items, q) {
  if (!q) return items;
  const query = cleanText(q);

  const recur = (arr) => {
    const out = [];
    for (const it of arr) {
      const title = cleanText(it.title ?? "");
      const children = it.children ? recur(it.children) : null;

      const matched = title.includes(query);
      if (matched || (children && children.length)) {
        out.push({ ...it, children: children && children.length ? children : undefined });
      }
    }
    return out;
  };

  return recur(items);
}

function collectOpenKeysForTree(items) {
  const keys = new Set();

  const walk = (arr, parents = []) => {
    for (const it of arr) {
      if (it.children?.length) {
        keys.add(it.key);
        walk(it.children, [...parents, it.key]);
      } else {
        parents.forEach((k) => keys.add(k));
      }
    }
  };

  walk(items);
  return Array.from(keys);
}

/* ---------------------------------------
   Component
---------------------------------------- */
export default function AppSidebar({
  width = 260,
  openKeys,
  setOpenKeys,
  selectedKeys,
  colorBgContainer, // (kept for compatibility; not required here)
}) {
  const [search, setSearch] = useState("");

  // If parent doesn't pass openKeys/setOpenKeys, fallback locally
  const [localOpenKeys, setLocalOpenKeys] = useState([]);
  const effectiveOpenKeys = openKeys ?? localOpenKeys;
  const effectiveSetOpenKeys = setOpenKeys ?? setLocalOpenKeys;

  const { sideItems, links } = useMemo(() => {
    // ✅ Routes map (adjust whenever you want)
    const links = {
      home: "/dashboard",

      // CRM
      crm_contact_group: "/crm/contact-groups",
      crm_contacts: "/crm/contacts",
      crm_customer: "/crm/customers",
      crm_supplier: "/crm/suppliers",
      crm_leads: "/crm/leads",
      crm_deals: "/crm/deals",
      crm_activity: "/crm/activity",

      // Workflow
      workflow_pending: "/workflow/pending-tasks",
      workflow_completed: "/workflow/completed-tasks",

      // Accounting
      acc_chart_of_accounts: "/accounting/chart-of-accounts",
      acc_bank_accounts: "/accounting/bank-accounts",
      acc_cheque_register: "/accounting/cheque-register",
      acc_cash_transfer: "/accounting/cash-transfer",
      acc_journal_voucher: "/accounting/journal-voucher",

      // Receivables
      recv_estimates: "/receivables/estimates",
      recv_sales_order: "/receivables/sales-orders",
      recv_invoices: "/receivables/invoices",
      recv_credit_notes: "/receivables/credit-notes",
      recv_payments: "/receivables/payments",
      recv_payment_in: "/receivables/payment-in",
      recv_payment_allocation: "/receivables/payment-allocation",
      recv_customers: "/receivables/customers",

      // Payables
      pay_orders: "/payables/orders",
      pay_bills: "/payables/bills",
      pay_expenses: "/payables/expenses",
      pay_payment_out: "/payables/payment-out",
      pay_debit_note: "/payables/debit-notes",
      pay_payment_allocation: "/payables/payment-allocation",
      pay_suppliers: "/payables/suppliers",
      pay_contractors: "/payables/contractors",

      // Product Master
      pm_product_category: "/product-categories",
      pm_products: "/product-master/products",
      pm_variant_attributes: "/product-master/variant-attributes",

      // Inventory
      inv_uom: "/inventory/uom",
      inv_warehouse_transfer: "/inventory/warehouse-transfer",
      inv_bom: "/inventory/bill-of-materials",
      inv_production_order: "/inventory/production-orders",
      inv_production_journal: "/inventory/production-journal",

      // HRM
      hrm: "/hrm",

      // Commission
      com_leads: "/commission/leads",
      com_rules: "/commission/rules",
      com_accruals: "/commission/accruals",
      com_payouts: "/commission/payouts",
      com_dispute: "/commission/disputes",

      // Others
      branches: "/branches",
      reports: "/reports",
      settings: "/settings",
    };

    const L = (key, label) => <Link href={links[key]}>{label}</Link>;

    // IMPORTANT: `title` is plain text used for searching.
    const sideItems = [
      { key: "home", icon: <HomeOutlined />, title: "Home", label: L("home", "Home") },

      {
        key: "crm",
        icon: <TeamOutlined />,
        title: "CRM",
        label: "CRM",
        children: [
          { key: "crm_contact_group", icon: <ContactsOutlined />, title: "Contact Group", label: L("crm_contact_group", "Contact Group") },
          { key: "crm_contacts", icon: <UserOutlined />, title: "Contacts", label: L("crm_contacts", "Contacts") },
          { key: "crm_customer", icon: <ShopOutlined />, title: "Customer", label: L("crm_customer", "Customer") },
          { key: "crm_supplier", icon: <ApartmentOutlined />, title: "Supplier", label: L("crm_supplier", "Supplier") },
          { key: "crm_leads", icon: <TrophyOutlined />, title: "Leads", label: L("crm_leads", "Leads") },
          { key: "crm_deals", icon: <IdcardOutlined />, title: "Deals", label: L("crm_deals", "Deals") },
          { key: "crm_activity", icon: <AlertOutlined />, title: "Activity", label: L("crm_activity", "Activity") },
        ],
      },

      {
        key: "workflow",
        icon: <CheckSquareOutlined />,
        title: "Workflow",
        label: "Workflow",
        children: [
          { key: "workflow_pending", icon: <ClockCircleOutlined />, title: "Pending Tasks", label: L("workflow_pending", "Pending Tasks") },
          { key: "workflow_completed", icon: <CheckSquareOutlined />, title: "Completed Tasks", label: L("workflow_completed", "Completed Tasks") },
        ],
      },

      {
        key: "accounting",
        icon: <BookOutlined />,
        title: "Accounting",
        label: "Accounting",
        children: [
          { key: "acc_chart_of_accounts", icon: <BookOutlined />, title: "Chart of Accounts", label: L("acc_chart_of_accounts", "Chart of Accounts") },
          { key: "acc_bank_accounts", icon: <BankOutlined />, title: "Bank Accounts", label: L("acc_bank_accounts", "Bank Accounts") },
          { key: "acc_cheque_register", icon: <FileTextOutlined />, title: "Cheque Register", label: L("acc_cheque_register", "Cheque Register") },
          { key: "acc_cash_transfer", icon: <SwapOutlined />, title: "Cash Transfer", label: L("acc_cash_transfer", "Cash Transfer") },
          { key: "acc_journal_voucher", icon: <TransactionOutlined />, title: "Journal Voucher", label: L("acc_journal_voucher", "Journal Voucher") },
        ],
      },

      {
        key: "receivables",
        icon: <InboxOutlined />,
        title: "Receivables",
        label: "Receivables",
        children: [
          { key: "recv_estimates", icon: <SnippetsOutlined />, title: "Quotation", label: L("recv_estimates", "Estimates") },
          { key: "recv_sales_order", icon: <ShoppingCartOutlined />, title: "Sales Order", label: L("recv_sales_order", "Sales Order") },
          { key: "recv_invoices", icon: <FileDoneOutlined />, title: "Invoice", label: L("recv_invoices", "Invoices") },
          { key: "recv_credit_notes", icon: <CreditCardOutlined />, title: "Credit Note", label: L("recv_credit_notes", "Credit Notes") },
          { key: "recv_payments", icon: <DollarOutlined />, title: "Customer Payment", label: L("recv_payments", "Payments") },
          { key: "recv_payment_in", icon: <WalletOutlined />, title: "Quick Receipt / Payment In", label: L("recv_payment_in", "Payment In") },
          { key: "recv_payment_allocation", icon: <ReconciliationOutlined />, title: "Payment Allocation", label: L("recv_payment_allocation", "Payment Allocation") },
          { key: "recv_customers", icon: <ShopOutlined />, title: "Customers", label: L("recv_customers", "Customers") },
        ],
      },

      {
        key: "payables",
        icon: <ShoppingOutlined />,
        title: "Payables",
        label: "Payables",
        children: [
          { key: "pay_orders", icon: <ShoppingCartOutlined />, title: "Purchase Order", label: L("pay_orders", "Order") },
          { key: "pay_bills", icon: <FileDoneOutlined />, title: "Purchase Bill", label: L("pay_bills", "Bills") },
          { key: "pay_expenses", icon: <DollarOutlined />, title: "Expenses", label: L("pay_expenses", "Expenses") },
          { key: "pay_payment_out", icon: <WalletOutlined />, title: "Quick Payment / Payment Out", label: L("pay_payment_out", "Payment Out") },
          { key: "pay_debit_note", icon: <CreditCardOutlined />, title: "Debit Note", label: L("pay_debit_note", "Debit Note") },
          { key: "pay_payment_allocation", icon: <ReconciliationOutlined />, title: "Payment Allocation", label: L("pay_payment_allocation", "Payment Allocation") },
          { key: "pay_suppliers", icon: <ApartmentOutlined />, title: "Suppliers", label: L("pay_suppliers", "Suppliers") },
          { key: "pay_contractors", icon: <IdcardOutlined />, title: "Contractors", label: L("pay_contractors", "Contractors") },
        ],
      },

      {
        key: "product_master",
        icon: <AppstoreOutlined />,
        title: "Product Master",
        label: "Product Master",
        children: [
          { key: "pm_product_category", icon: <TagsOutlined />, title: "Product Category", label: L("pm_product_category", "Product Category") },
          { key: "pm_products", icon: <BarcodeOutlined />, title: "Products", label: L("pm_products", "Products") },
          { key: "pm_variant_attributes", icon: <BuildOutlined />, title: "Variant Attributes", label: L("pm_variant_attributes", "Variant Attributes") },
        ],
      },

      {
        key: "inventory",
        icon: <DatabaseOutlined />,
        title: "Inventory",
        label: "Inventory",
        children: [
          { key: "inv_uom", icon: <BgColorsOutlined />, title: "Unit of Measurement", label: L("inv_uom", "Unit of Measurement") },
          { key: "inv_warehouse_transfer", icon: <BankOutlined />, title: "Warehouse Transfer", label: L("inv_warehouse_transfer", "Warehouse Transfer") },
          { key: "inv_bom", icon: <DeploymentUnitOutlined />, title: "Bill of Materials", label: L("inv_bom", "Bill of Materials") },
          { key: "inv_production_order", icon: <FundProjectionScreenOutlined />, title: "Production Order", label: L("inv_production_order", "Production Order") },
          { key: "inv_production_journal", icon: <TransactionOutlined />, title: "Production Journal", label: L("inv_production_journal", "Production Journal") },
        ],
      },

      { key: "hrm", icon: <TeamOutlined />, title: "HRM", label: L("hrm", "HRM") },

      {
        key: "commission",
        icon: <DollarOutlined />,
        title: "Commission",
        label: "Commission",
        children: [
          { key: "com_leads", icon: <TrophyOutlined />, title: "Leads", label: L("com_leads", "Leads") },
          { key: "com_rules", icon: <SettingOutlined />, title: "Rules", label: L("com_rules", "Rules") },
          { key: "com_accruals", icon: <TransactionOutlined />, title: "Accruals", label: L("com_accruals", "Accruals") },
          { key: "com_payouts", icon: <WalletOutlined />, title: "Payouts", label: L("com_payouts", "Payouts") },
          { key: "com_dispute", icon: <AlertOutlined />, title: "Dispute", label: L("com_dispute", "Dispute") },
        ],
      },

      { key: "reports", icon: <BarChartOutlined />, title: "Reports", label: L("reports", "Reports") },
      { key: "branches", icon: <ApartmentOutlined />, title: "Branches", label: L("branches", "Branches") },
      { key: "settings", icon: <SettingOutlined />, title: "Settings", label: L("settings", "Settings") },
    ];

    return { sideItems, links };
  }, []);

  // Search filtering (keeps parent groups if any child matches)
  const filteredItems = useMemo(() => filterMenuTree(sideItems, search), [sideItems, search]);

  // Auto-open matching groups during search
  useEffect(() => {
    if (!search) return;
    effectiveSetOpenKeys(collectOpenKeysForTree(filteredItems));
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [search, filteredItems]);

  // Quick Add overlay (floats to the RIGHT of the button)
  const quickAddOverlay = useMemo(() => {
    const go = (href) => href && router.visit(href);

    const Item = ({ label, href }) => (
      <div
        key={label}
        onClick={() => go(href)}
        style={{
          cursor: "pointer",
          padding: "8px 10px",
          borderRadius: 10,
          display: "flex",
          alignItems: "center",
          gap: 8,
          whiteSpace: "nowrap",
        }}
        onMouseEnter={(e) => (e.currentTarget.style.background = "#f5f5f5")}
        onMouseLeave={(e) => (e.currentTarget.style.background = "transparent")}
      >
        <PlusOutlined style={{ fontSize: 12 }} />
        <Text>{label}</Text>
      </div>
    );

    const Section = ({ title, items }) => (
      <div style={{ padding: 12 }}>
        <Text type="secondary" style={{ fontSize: 12, letterSpacing: 0.8 }}>
          {title}
        </Text>
        <Divider style={{ margin: "8px 0" }} />
        <div style={{ display: "flex", flexDirection: "column", gap: 2 }}>
          {items.map((it) => (
            <Item key={it.label} label={it.label} href={it.href} />
          ))}
        </div>
      </div>
    );

    return (
      <div
        style={{
          background: "#fff",
          borderRadius: 14,
          boxShadow: "0 12px 30px rgba(0,0,0,0.15)",
          overflow: "hidden",
          minWidth: 860,
        }}
      >
        <div style={{ display: "grid", gridTemplateColumns: "repeat(4, 1fr)" }}>
          <div style={{ borderRight: "1px solid #f0f0f0" }}>
            <Section
              title="GENERAL"
              items={[
                { label: "Customer", href: links.recv_customers ?? "/receivables/customers" },
                { label: "Supplier", href: links.pay_suppliers ?? "/payables/suppliers" },
                { label: "Products", href: links.pm_products ?? "/product-master/products" },
                { label: "Accounts", href: links.acc_chart_of_accounts ?? "/accounting/chart-of-accounts" },
                { label: "Accounts Group", href: links.acc_chart_of_accounts ?? "/accounting/chart-of-accounts" },
              ]}
            />
          </div>

          <div style={{ borderRight: "1px solid #f0f0f0" }}>
            <Section
              title="SALES"
              items={[
                { label: "Quotation", href: links.recv_estimates ?? "/receivables/estimates" },
                { label: "Sales Order", href: links.recv_sales_order ?? "/receivables/sales-orders" },
                { label: "Invoice", href: links.recv_invoices ?? "/receivables/invoices" },
                { label: "Customer Payment", href: links.recv_payments ?? "/receivables/payments" },
                { label: "Credit Note", href: links.recv_credit_notes ?? "/receivables/credit-notes" },
              ]}
            />
          </div>

          <div style={{ borderRight: "1px solid #f0f0f0" }}>
            <Section
              title="PURCHASE"
              items={[
                { label: "Purchase Order", href: links.pay_orders ?? "/payables/orders" },
                { label: "Purchase Bill", href: links.pay_bills ?? "/payables/bills" },
                { label: "Expenses", href: links.pay_expenses ?? "/payables/expenses" },
                { label: "Supplier Payment", href: links.pay_payment_out ?? "/payables/payment-out" },
                { label: "Debit Note", href: links.pay_debit_note ?? "/payables/debit-notes" },
              ]}
            />
          </div>

          <div>
            <Section
              title="ACCOUNTING"
              items={[
                { label: "Journal Voucher", href: links.acc_journal_voucher ?? "/accounting/journal-voucher" },
                { label: "Cash Transfer", href: links.acc_cash_transfer ?? "/accounting/cash-transfer" },
                { label: "Quick Payment", href: links.pay_payment_out ?? "/payables/payment-out" },
                { label: "Quick Receipt", href: links.recv_payment_in ?? "/receivables/payment-in" },
              ]}
            />
          </div>
        </div>
      </div>
    );
  }, [links]);

  return (
    <Sider width={width} 
    style={{
      background: colorBgContainer,
      position: "fixed",
      top: 64,
      left: 0,
      height: `calc(100vh - 64px)`,
      borderInlineEnd: "1px solid #f0f0f0",
    }} 
    className="app-sidebar border-end">
       <div style={{ padding: 10, position: "sticky", top: 0, zIndex: 2, background: "#f5f5f5" }}>
        <Row gutter={8} align="middle">
          <Col className="my-1 w-100">
            <Dropdown
              trigger={["click"]}
              placement="topRight"          // anchor at bottom-right of the button
              arrow
              destroyPopupOnHide
              overlayStyle={{ marginLeft: 8 }} // nudge the panel to the RIGHT (float)
              dropdownRender={() => quickAddOverlay}
            >
              <Button icon={<PlusOutlined />} iconPlacement="end" className="w-100">Quick Add</Button>
            </Dropdown>
          </Col>
          <Col flex="auto">
            <Input
              allowClear
              value={search}
              onChange={(e) => setSearch(e.target.value)}
              prefix={<SearchOutlined />}
              placeholder="Search menu..."
            />
          </Col>


        </Row>
      </div>

      <Menu
        mode="inline"
        className="app-sidebar__menu"

        items={filteredItems}
        onOpenChange={effectiveSetOpenKeys}
        selectedKeys={selectedKeys}
        style={{ height: "100%", borderInlineEnd: 0, background: "#f5f5f5" }}
      />
    </Sider>
  );
}
