export type SectionId = "profile" | "status" | "categories" | "fundraising" | "updates";

export interface StatusItem {
  icon: string;
  label: string;
  value: string;
  detail?: string;
  actionLabel?: string;
}

export interface CategoryItem {
  icon: string;
  label: string;
  target: SectionId;
}

export interface GoalItem {
  id: string;
  title: string;
  subtitle: string;
  image: string;
  imageAlt: string;
  progressLabel: string;
  progressAmount: string;
  targetAmount: string;
  progress: number;
  status?: string;
  updated?: string;
  tags?: string[];
  actionLabel: string;
}

export interface NavigationItem {
  icon: string;
  label: string;
  target: SectionId;
  hasUnread?: boolean;
}

export const statusItems: StatusItem[] = [
  { icon: "calendar_today", label: "Age", value: "23" },
  { icon: "location_on", label: "Current Address", value: "Anchalasseri House, Njarakkal" },
  {
    icon: "work",
    label: "Job",
    value: "Digital Marketer",
    detail: "IZIN Designs Interior Studio, Kerala, India",
  },
  {
    icon: "business_center",
    label: "Business",
    value: "Founder & Manager",
    detail: "Velnex Business Management and Operations FZE LLC (UAE) (Early Stage)",
  },
  {
    icon: "school",
    label: "Academics",
    value: "Enrolled in Plus Two - Commerce",
    detail: "Board of Open Schooling and Skill Education (Government of Sikkim, India)",
  },
  {
    icon: "account_balance_wallet",
    label: "Financial Status",
    value: "Private Portfolio",
    actionLabel: "View Net Worth",
  },
];

export const categories: CategoryItem[] = [
  { icon: "monitoring", label: "Track Progress", target: "status" },
  { icon: "rocket_launch", label: "Velnex", target: "updates" },
  { icon: "school", label: "Education", target: "fundraising" },
  { icon: "credit_card", label: "Financial Reset", target: "fundraising" },
  { icon: "handshake", label: "Partner With Me", target: "fundraising" },
  { icon: "menu_book", label: "My Story", target: "profile" },
];

export const goals: GoalItem[] = [
  {
    id: "bosse-plus-two",
    title: "BOSSE Plus Two",
    subtitle: "Education Sponsorship",
    image: "/bosse-education.jpg",
    imageAlt: "An open book, laptop and graduation cap representing education and progress",
    progressLabel: "Raised",
    progressAmount: "₹7,000",
    targetAmount: "₹35,000",
    progress: 20,
    status: "Admission Stage",
    updated: "Updated 14 July 2026",
    actionLabel: "View Goal",
  },
  {
    id: "debt-consolidation",
    title: "Debt Consolidation",
    subtitle: "Financial Stabilisation",
    image: "/debt-consolidation.jpg",
    imageAlt: "Credit cards, a pen and wallet representing financial stability",
    progressLabel: "Cleared",
    progressAmount: "₹5,000",
    targetAmount: "₹43,000",
    progress: 12,
    tags: ["Ashva", "Signet", "Slice"],
    actionLabel: "View Breakdown",
  },
];

export const navigation: NavigationItem[] = [
  { icon: "dashboard", label: "Home", target: "profile" },
  { icon: "explore", label: "Explore", target: "categories" },
  { icon: "timeline", label: "Journey", target: "status" },
  { icon: "notifications", label: "Updates", target: "updates", hasUnread: true },
  { icon: "person", label: "Profile", target: "profile" },
];
