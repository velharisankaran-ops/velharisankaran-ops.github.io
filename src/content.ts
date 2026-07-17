export type SectionId = "profile" | "status" | "categories" | "fundraising" | "updates" | string;

export interface StatusItem {
  icon: string;
  label: string;
  value: string;
  detail?: string;
  subDetail?: string;
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
  category?: string;
  subtitle: string;
  image: string;
  imageAlt: string;
  progressLabel: string;
  progressAmount: string;
  targetAmount: string;
  deadline?: string;
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
  url?: string;
}

export interface GoalDetailRow {
  label: string;
  detail?: string;
  value: string;
}

export interface GoalMilestone {
  title: string;
  description: string;
  state: "complete" | "pending";
}

export interface GoalDetails {
  slug: string;
  title: string;
  subtitle: string;
  category: string;
  heroImage: string;
  raisedAmount: string;
  targetAmount: string;
  supporters: number;
  progress: number;
  remainingAmount: string;
  invoice: string;
  feeBreakdown: GoalDetailRow[];
  enrollmentDetails: GoalDetailRow[];
  contributors: GoalDetailRow[];
  milestones: GoalMilestone[];
  institutionPaymentUrl: string;
}

export const statusItems: StatusItem[] = [
  { icon: "calendar_today", label: "Age", value: "23" },
  { icon: "location_on", label: "Current Address", value: "Anchalasseri House, Njarakkal, Vypin, Kochi, Kerala, India, PIN 682505" },
  {
    icon: "work",
    label: "Professional Experience",
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
    label: "Academic & Education",
    value: "Enrolled in Plus Two - Commerce",
    detail: "Board of Open Schooling and Skill Education (Government of Sikkim, India)",
  },
  {
    icon: "account_balance_wallet",
    label: "Financial Status",
    value: "Private Portfolio",
    actionLabel: "View Financials",
  },
];

export const categories: CategoryItem[] = [
  { icon: "school", label: "Academic & Education", target: "status" },
  { icon: "work", label: "Professional Experience", target: "/professional-experience.html" },
  { icon: "rocket_launch", label: "Velnex", target: "updates" },
  { icon: "menu_book", label: "My Story", target: "profile" },
];

export const goals: GoalItem[] = [
  {
    id: "bosse-plus-two",
    category: "Education Sponsorship",
    title: "Plus Two - Commerce",
    subtitle: "Open Schooling",
    image: "/bosse-education.jpg",
    imageAlt: "An open book, laptop and graduation cap representing education and progress",
    progressLabel: "Raised",
    progressAmount: "₹9,000",
    targetAmount: "₹35,000",
    deadline: "15 Aug 2026",
    progress: 26,
    status: "Admission Stage",
    updated: "Last updated 14 July 2026",
    actionLabel: "View",
  },
  {
    id: "debt-consolidation",
    category: "Financial Stabilisation",
    title: "Debt Consolidation",
    subtitle: "Multiple Active Accounts",
    image: "/debt-consolidation.jpg",
    imageAlt: "Credit cards, a pen and wallet representing financial stability",
    progressLabel: "Cleared",
    progressAmount: "₹5,000",
    targetAmount: "₹43,000",
    deadline: "31 Dec 2026",
    progress: 12,
    tags: ["Ashva", "Signet", "Slice"],
    actionLabel: "View Breakdown",
  },
];

export const navigation: NavigationItem[] = [
  { icon: "home", label: "Home", target: "profile", url: "/" },
  { icon: "account_balance_wallet", label: "Fundrais", target: "fundraising", url: "/goal-details.html" },
  { icon: "rocket_launch", label: "Velnex", target: "updates", url: "/#updates" },
];

export const plusTwoGoal: GoalDetails = {
  slug: "plus-two",
  title: "Plus Two - Commerce",
  subtitle: "BOSSE Board Higher Secondary Certification",
  category: "Education Sponsorship",
  heroImage: "/plus-two-hero.jpg",
  raisedAmount: "₹9,000",
  targetAmount: "₹35,000",
  supporters: 1,
  progress: 25.7,
  remainingAmount: "₹26,000",
  invoice: "Inv #BOS-000218",
  feeBreakdown: [
    { label: "Registration Fee", detail: "BOSSE Official Fees: Mandatory 5-year enrollment", value: "₹1,000" },
    { label: "Program Fee", detail: "BOSSE Official Fees: 5 Core Commerce subjects", value: "₹6,000" },
    { label: "Examination Fee", detail: "BOSSE Official Fees: ₹500 per paper × 5 subjects", value: "₹2,500" },
    { label: "AMET Class & Assistance", detail: "AMET Institute Fees: Tuition, study notes, and support", value: "₹25,500" },
  ],
  enrollmentDetails: [
    { label: "Course Enrolled", value: "Plus Two (+2) / Senior Secondary (Commerce Stream)" },
    { label: "Examining Board", value: "Board of Open Schooling and Skill Education (BOSSE)" },
    { label: "Enrolling Institution", value: "AMET Global (Third Floor, Central Tower, Thiruvalla, Kerala)" },
    { label: "Institution Type", value: "Private Training & Educational Consultancy Center" },
    { label: "Official Website", value: "ametglobal.com" },
  ],
  contributors: [
    { label: "Self-Funded (Salary)", value: "₹9,000" },
  ],
  milestones: [
    { title: "Enrollment Secured", description: "Initial registration and core books purchased.", state: "complete" },
    { title: "Exam Fee Deposit", description: "Target: ₹20,000 to clear board examination dues.", state: "pending" },
  ],
  institutionPaymentUrl: "https://zohosecurepay.in/books/ametglobal/secure?CInvoiceID=2-f285617c4a13c5d33f34b9b617687bae8ae466897c6649607b021e56c356f1a0646a53dd8ed69f1b773aba0674c4215bdaee8aef44d1dd5a127687162be70f52bf747bf136c83d3b#/invoices/payment?invoice_ids=2-f285617c4a13c5d33f34b9b617687bae8ae466897c6649607b021e56c356f1a0646a53dd8ed69f1b773aba0674c4215bdaee8aef44d1dd5a127687162be70f52bf747bf136c83d3b",
};

export const plusTwoGoalRoute = "/Fundraising-goals/Acadamics";
