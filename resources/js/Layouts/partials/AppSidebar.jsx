// resources/js/Layouts/partials/AppSidebar.jsx
import React, { useMemo } from "react";
import { Layout, Menu } from "antd";
import { Link } from "@inertiajs/react";

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
} from "@ant-design/icons";

const { Sider } = Layout;

export default function AppSidebar({
  width = 220,
  openKeys,
  setOpenKeys,
  selectedKeys,
  colorBgContainer,
}) {
  const sideItems = useMemo(() => {
    // ✅ Manually defined links per menu item (edit later)
    const links = {
      home: "/",

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
      reports: "/reports",
      settings: "/settings",
    };

    const L = (key, label) => <Link href={links[key]}>{label}</Link>;

    return [
      { key: "home", icon: <HomeOutlined />, label: L("home", "Home") },

      {
        key: "crm",
        icon: <TeamOutlined />,
        label: "CRM",
        children: [
          { key: "crm_contact_group", icon: <ContactsOutlined />, label: L("crm_contact_group", "Contact Group") },
          { key: "crm_contacts", icon: <UserOutlined />, label: L("crm_contacts", "Contacts") },
          { key: "crm_customer", icon: <ShopOutlined />, label: L("crm_customer", "Customer") },
          { key: "crm_supplier", icon: <ApartmentOutlined />, label: L("crm_supplier", "Supplier") },
          { key: "crm_leads", icon: <TrophyOutlined />, label: L("crm_leads", "Leads") },
          { key: "crm_deals", icon: <IdcardOutlined />, label: L("crm_deals", "Deals") },
          { key: "crm_activity", icon: <AlertOutlined />, label: L("crm_activity", "Activity") },
        ],
      },

      {
        key: "workflow",
        icon: <CheckSquareOutlined />,
        label: "Workflow",
        children: [
          { key: "workflow_pending", icon: <ClockCircleOutlined />, label: L("workflow_pending", "Pending Tasks") },
          { key: "workflow_completed", icon: <CheckSquareOutlined />, label: L("workflow_completed", "Completed Tasks") },
        ],
      },

      {
        key: "accounting",
        icon: <BookOutlined />,
        label: "Accounting",
        children: [
          { key: "acc_chart_of_accounts", icon: <BookOutlined />, label: L("acc_chart_of_accounts", "Chart of Accounts") },
          { key: "acc_bank_accounts", icon: <BankOutlined />, label: L("acc_bank_accounts", "Bank Accounts") },
          { key: "acc_cheque_register", icon: <FileTextOutlined />, label: L("acc_cheque_register", "Cheque Register") },
          { key: "acc_cash_transfer", icon: <SwapOutlined />, label: L("acc_cash_transfer", "Cash Transfer") },
          { key: "acc_journal_voucher", icon: <TransactionOutlined />, label: L("acc_journal_voucher", "Journal Voucher") },
        ],
      },

      {
        key: "receivables",
        icon: <InboxOutlined />,
        label: "Receivables",
        children: [
          { key: "recv_estimates", icon: <SnippetsOutlined />, label: L("recv_estimates", "Estimates") },
          { key: "recv_sales_order", icon: <ShoppingCartOutlined />, label: L("recv_sales_order", "Sales Order") },
          { key: "recv_invoices", icon: <FileDoneOutlined />, label: L("recv_invoices", "Invoices") },
          { key: "recv_credit_notes", icon: <CreditCardOutlined />, label: L("recv_credit_notes", "Credit Notes") },
          { key: "recv_payments", icon: <DollarOutlined />, label: L("recv_payments", "Payments") },
          { key: "recv_payment_in", icon: <WalletOutlined />, label: L("recv_payment_in", "Payment In") },
          { key: "recv_payment_allocation", icon: <ReconciliationOutlined />, label: L("recv_payment_allocation", "Payment Allocation") },
          { key: "recv_customers", icon: <ShopOutlined />, label: L("recv_customers", "Customers") },
        ],
      },

      {
        key: "payables",
        icon: <ShoppingOutlined />,
        label: "Payables",
        children: [
          { key: "pay_orders", icon: <ShoppingCartOutlined />, label: L("pay_orders", "Order") },
          { key: "pay_bills", icon: <FileDoneOutlined />, label: L("pay_bills", "Bills") },
          { key: "pay_expenses", icon: <DollarOutlined />, label: L("pay_expenses", "Expenses") },
          { key: "pay_payment_out", icon: <WalletOutlined />, label: L("pay_payment_out", "Payment Out") },
          { key: "pay_debit_note", icon: <CreditCardOutlined />, label: L("pay_debit_note", "Debit Note") },
          { key: "pay_payment_allocation", icon: <ReconciliationOutlined />, label: L("pay_payment_allocation", "Payment Allocation") },
          { key: "pay_suppliers", icon: <ApartmentOutlined />, label: L("pay_suppliers", "Suppliers") },
          { key: "pay_contractors", icon: <IdcardOutlined />, label: L("pay_contractors", "Contractors") },
        ],
      },

      {
        key: "product_master",
        icon: <AppstoreOutlined />,
        label: "Product Master",
        children: [
          { key: "pm_product_category", icon: <TagsOutlined />, label: L("pm_product_category", "Product Category") },
          { key: "pm_products", icon: <BarcodeOutlined />, label: L("pm_products", "Products") },
          { key: "pm_variant_attributes", icon: <BuildOutlined />, label: L("pm_variant_attributes", "Variant Attributes") },
        ],
      },

      {
        key: "inventory",
        icon: <DatabaseOutlined />,
        label: "Inventory",
        children: [
          { key: "inv_uom", icon: <BgColorsOutlined />, label: L("inv_uom", "Unit of Measurement") },
          { key: "inv_warehouse_transfer", icon: <BankOutlined />, label: L("inv_warehouse_transfer", "Warehouse Transfer") },
          { key: "inv_bom", icon: <DeploymentUnitOutlined />, label: L("inv_bom", "Bill of Materials") },
          { key: "inv_production_order", icon: <FundProjectionScreenOutlined />, label: L("inv_production_order", "Production Order") },
          { key: "inv_production_journal", icon: <TransactionOutlined />, label: L("inv_production_journal", "Production Journal") },
        ],
      },

      { key: "hrm", icon: <TeamOutlined />, label: L("hrm", "HRM") },

      {
        key: "commission",
        icon: <DollarOutlined />,
        label: "Commission",
        children: [
          { key: "com_leads", icon: <TrophyOutlined />, label: L("com_leads", "Leads") },
          { key: "com_rules", icon: <SettingOutlined />, label: L("com_rules", "Rules") },
          { key: "com_accruals", icon: <TransactionOutlined />, label: L("com_accruals", "Accruals") },
          { key: "com_payouts", icon: <WalletOutlined />, label: L("com_payouts", "Payouts") },
          { key: "com_dispute", icon: <AlertOutlined />, label: L("com_dispute", "Dispute") },
        ],
      },

      { key: "reports", icon: <BarChartOutlined />, label: L("reports", "Reports") },
      { key: "settings", icon: <SettingOutlined />, label: L("settings", "Settings") },
    ];
  }, []);

  return (
    <Sider width={width} style={{ background: colorBgContainer }}>
      <Menu
        mode="inline"
        items={sideItems}
        //openKeys={openKeys}
        onOpenChange={setOpenKeys}
        selectedKeys={selectedKeys}
        style={{ height: "100%", borderInlineEnd: 0 }}
      />
    </Sider>
  );
}
