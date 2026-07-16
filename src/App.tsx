import { useEffect, useRef, useState } from "react";
import { categories, goals, navigation, plusTwoGoalRoute, statusItems, type SectionId } from "./content";
import { GoalDetailsPage } from "./GoalDetails";

const sectionLabels: Record<SectionId, string> = {
  profile: "Home",
  status: "Journey",
  categories: "Explore",
  fundraising: "Explore",
  updates: "Updates",
};

function Icon({ name, filled = false }: { name: string; filled?: boolean }) {
  return (
    <span className={`material-symbols-outlined${filled ? " material-symbols-outlined--filled" : ""}`} aria-hidden="true">
      {name}
    </span>
  );
}

function HomePage() {
  const [activeSection, setActiveSection] = useState<SectionId>("profile");
  const [notice, setNotice] = useState("");
  const noticeTimer = useRef<number | undefined>(undefined);

  const scrollToSection = (target: SectionId) => {
    document.getElementById(target)?.scrollIntoView({ behavior: "smooth", block: "start" });
    setActiveSection(target);
  };

  const announceComingSoon = (page: string) => {
    window.clearTimeout(noticeTimer.current);
    setNotice(`${page} is coming next.`);
    noticeTimer.current = window.setTimeout(() => setNotice(""), 3200);
  };

  useEffect(() => {
    const sections = (["profile", "status", "categories", "fundraising", "updates"] as SectionId[])
      .map((id) => document.getElementById(id))
      .filter((section): section is HTMLElement => Boolean(section));

    const observer = new IntersectionObserver(
      (entries) => {
        const visible = entries
          .filter((entry) => entry.isIntersecting)
          .sort((a, b) => b.intersectionRatio - a.intersectionRatio)[0];
        if (visible) setActiveSection(visible.target.id as SectionId);
      },
      { rootMargin: "-20% 0px -65%", threshold: [0.1, 0.4] },
    );

    sections.forEach((section) => observer.observe(section));
    return () => {
      observer.disconnect();
      window.clearTimeout(noticeTimer.current);
    };
  }, []);

  return (
    <div className="app-shell">
      <a className="skip-link" href="#profile">Skip to content</a>

      <header className="top-bar">
        <div className="top-bar__inner">
          <span className="brand">Velhari Sankaran</span>
          <button className="icon-button" type="button" aria-label="Open updates" onClick={() => scrollToSection("updates")}>
            <Icon name="notifications" />
          </button>
        </div>
      </header>

      <main className="main-content">
        <section className="profile-section section-anchor" id="profile" aria-labelledby="profile-title">
          <div className="avatar-frame">
            <img src="/profile.jpg" alt="Velhari Sankaran, founder of Velnex" width="128" height="128" />
          </div>
          <div className="profile-copy">
            <h1 id="profile-title">Velhari Sankaran</h1>
            <p className="eyebrow">Documenting my journy</p>
            <div className="intro-copy">
              <p>Building my</p>
              <p><strong>education</strong>, <strong>financial stability</strong>, &amp; <strong>business</strong>.</p>
              <p>Inviting you to support my key milestones.</p>
            </div>
            <button className="button button--secondary profile-cta" type="button" onClick={() => scrollToSection("fundraising")}>
              <span>View Fundraising Goals</span>
              <Icon name="arrow_forward" />
            </button>
          </div>
        </section>

        <section className="section-block section-anchor" id="status" aria-labelledby="status-title">
          <h2 id="status-title">Current Status</h2>
          <div className="status-card">
            {statusItems.map((item, index) => (
              <div className={`status-row${index === statusItems.length - 1 ? " status-row--last" : ""}`} key={item.label}>
                <span className="status-row__icon"><Icon name={item.icon} /></span>
                <div className="status-row__content">
                  <div>
                    <p className="field-label">{item.label}</p>
                    <p className="field-value">{item.value}</p>
                    {item.detail && <p className="field-detail">{item.detail}</p>}
                  </div>
                  {item.actionLabel && (
                    <button className="button button--primary button--small" type="button" onClick={() => announceComingSoon("Financial Position & Net Worth")}>
                      <span>{item.actionLabel}</span>
                      <Icon name="open_in_new" />
                    </button>
                  )}
                </div>
              </div>
            ))}
          </div>
        </section>

        <section className="section-block section-anchor" id="categories" aria-labelledby="categories-title">
          <h2 id="categories-title">Key Categories</h2>
          <div className="category-grid">
            {categories.map((category) => (
              <button className="category-card" type="button" key={category.label} onClick={() => scrollToSection(category.target)}>
                <Icon name={category.icon} />
                <span>{category.label}</span>
              </button>
            ))}
          </div>
        </section>

        <section className="section-block section-anchor" id="fundraising" aria-labelledby="fundraising-title">
          <h2 id="fundraising-title">Fundraising Goals</h2>
          <div className="goal-list">
            {goals.map((goal) => (
              <article className="goal-card" id={goal.id} key={goal.id}>
                <div className="goal-card__image">
                  <img src={goal.image} alt={goal.imageAlt} loading="lazy" />
                </div>
                <div className="goal-card__body">
                  <div>
                    <div className="goal-card__heading">
                      <div>
                        <h3>{goal.title}</h3>
                        <p>{goal.subtitle}</p>
                      </div>
                      {goal.status && <span className="status-badge">{goal.status}</span>}
                    </div>

                    <div className="progress-area" aria-label={`${goal.progress}% complete`}>
                      <div className="progress-labels">
                        <span>{goal.progressLabel}: <strong>{goal.progressAmount}</strong></span>
                        <span>Target: {goal.targetAmount}</span>
                      </div>
                      <div className="progress-track" role="progressbar" aria-valuemin={0} aria-valuemax={100} aria-valuenow={goal.progress}>
                        <span style={{ width: `${goal.progress}%` }} />
                      </div>
                    </div>

                    {goal.tags && (
                      <div className="tag-list" aria-label="Debt accounts">
                        {goal.tags.map((tag) => <span key={tag}>{tag}</span>)}
                      </div>
                    )}
                  </div>

                  <div className={`goal-card__footer${goal.updated ? "" : " goal-card__footer--end"}`}>
                    {goal.updated && <span className="updated-label">{goal.updated}</span>}
                    <button
                      className={`button ${goal.status ? "button--primary" : "button--secondary"}`}
                      type="button"
                      onClick={() => goal.id === "bosse-plus-two" ? window.location.hash = plusTwoGoalRoute : announceComingSoon(goal.title)}
                    >
                      {goal.actionLabel}
                    </button>
                  </div>
                </div>
              </article>
            ))}
          </div>
        </section>

        <section className="section-block section-anchor latest-section" id="updates" aria-labelledby="updates-title">
          <h2 id="updates-title">Latest Updates</h2>
          <article className="update-card">
            <div className="update-card__header">
              <span className="update-icon"><Icon name="campaign" /></span>
              <div>
                <h3>Milestone Reached!</h3>
                <p>2 hours ago</p>
              </div>
            </div>
            <p>Successfully completed the first phase of the Velnex registration process in the UAE. Moving towards the operational setup next week.</p>
          </article>
        </section>
      </main>

      <nav className="bottom-nav" aria-label="Primary navigation">
        <div className="bottom-nav__inner">
          {navigation.map((item) => {
            const isActive = sectionLabels[activeSection] === item.label;
            return (
              <button className={`nav-item${isActive ? " nav-item--active" : ""}`} type="button" key={item.label} onClick={() => scrollToSection(item.target)} aria-current={isActive ? "page" : undefined}>
                <span className="nav-item__icon">
                  <Icon name={item.icon} filled={isActive} />
                  {item.hasUnread && <span className="unread-dot" aria-hidden="true" />}
                </span>
                <span>{item.label}</span>
              </button>
            );
          })}
        </div>
      </nav>

      <div className={`toast${notice ? " toast--visible" : ""}`} role="status" aria-live="polite" aria-atomic="true">
        {notice}
      </div>
    </div>
  );
}

export function App() {
  const [route, setRoute] = useState(window.location.hash);

  useEffect(() => {
    const handleRoute = () => setRoute(window.location.hash);
    window.addEventListener("hashchange", handleRoute);
    return () => window.removeEventListener("hashchange", handleRoute);
  }, []);

  return route.startsWith(`#${plusTwoGoalRoute}`) ? <GoalDetailsPage /> : <HomePage />;
}
