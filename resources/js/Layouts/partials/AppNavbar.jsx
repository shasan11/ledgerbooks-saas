// resources/js/Layouts/partials/AppNavbar.jsx
import React, { useMemo } from "react";
import { Link } from "@inertiajs/react";
import { Layout, Dropdown, Avatar, Space } from "antd";
import {
  DashboardOutlined,
  UserOutlined,
  DownOutlined,
  SettingOutlined,
  LogoutOutlined,
} from "@ant-design/icons";

const { Header } = Layout;

export default function AppNavbar({ user }) {
  const NAV_HEIGHT = 64;

  // For now, all top links also go to homepage
  const toHome = (text) => <Link href="/">{text}</Link>;

  const topItems = useMemo(
    () => [
      { key: "dashboard", icon: <DashboardOutlined />, label: toHome("Dashboard") },
      { key: "nav2", label: toHome("Nav 2") },
      { key: "nav3", label: toHome("Nav 3") },
    ],
    []
  );

  const userMenuItems = [
    {
      key: "profile",
      icon: <SettingOutlined />,
      label: <Link href={route("profile.edit")}>Profile</Link>,
    },
    { type: "divider" },
    {
      key: "logout",
      icon: <LogoutOutlined />,
      label: (
        <Link href={route("logout")} method="post" as="button">
          Log out
        </Link>
      ),
    },
  ];

  return (
    <Header
      style={{
        position: "fixed",
        top: 0,
        left: 0,
        right: 0,
        height: NAV_HEIGHT,
        lineHeight: `${NAV_HEIGHT}px`,
        zIndex: 1000,
        display: "flex",
        alignItems: "center",
        gap: 12,
        paddingInline: 16,
      }}
    >
      {/* Brand / Logo */}
      <Link href="/" style={{ color: "white", fontWeight: 700 }}>
        LedgerBooks
      </Link>

      {/* Top menu placeholder */}
      <div style={{ flex: 1 }}>sa</div>

      {/* User dropdown */}
      <Dropdown menu={{ items: userMenuItems }} trigger={["click"]}>
        <a onClick={(e) => e.preventDefault()} style={{ color: "white" }}>
          <Space>
            <Avatar size="small" icon={<UserOutlined />} />
            <span>{user?.name ?? "User"}</span>
            <DownOutlined />
          </Space>
        </a>
      </Dropdown>
    </Header>
  );
}
