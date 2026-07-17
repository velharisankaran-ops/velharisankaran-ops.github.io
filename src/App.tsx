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

      <header className="top-bar top-bar--stitch">
        <div className="top-bar__inner">
          <div className="top-bar__identity">
            <span className="brand-subtitle eyebrow--top" style={{ marginBottom: 0 }}>DOCUMENTING MY JOURNEY</span>
          </div>
          <div className="top-bar__actions">
            <button className="icon-button" type="button" aria-label="Open updates" onClick={() => scrollToSection("updates")}>
              <Icon name="notifications" />
            </button>
          </div>
        </div>
      </header>

      <main className="main-content">
        <div style={{ display: 'flex', flexDirection: 'column', gap: '12px' }}>
          <section className="profile-section section-anchor" id="profile" aria-labelledby="profile-title">
            <div style={{ display: 'flex', flexDirection: 'column', alignItems: 'center', gap: '4px' }}>
              <div className="avatar-frame" style={{ margin: 0 }}>
                <img src="/profile.jpg" alt="Velhari Sankaran" width="128" height="128" />
              </div>
              <h1 className="profile-name">Velhari Sankaran</h1>
            </div>
            
            <div className="profile-copy" style={{ display: 'flex', flexDirection: 'column', gap: '8px', alignItems: 'center', marginTop: '24px' }}>
              <h2 id="profile-title" className="hero-title">Building my Education, Financial stability, &amp; Business.</h2>
              <p className="hero-subtitle">Inviting you to support my key milestones.</p>
              <button className="button button--secondary profile-cta" type="button" onClick={() => scrollToSection("fundraising")}>
                <span>View Fundraising</span>
                <Icon name="arrow_forward" />
              </button>
            </div>
          </section>

          <section className="section-block section-anchor" id="status" aria-labelledby="status-title">
            <h2 id="status-title" className="section-header-with-icon">
              <Icon name="person" />
              Profile Details
            </h2>
            <div className="profile-details-list">
              {statusItems.map((item, index) => (
                <div className={`status-row${index === statusItems.length - 1 ? " status-row--last" : ""}`} key={item.label}>
                  <div className="status-row__content" style={{ paddingLeft: 0 }}>
                    <div>
                      <p className="field-label">{item.label}</p>
                      <p className="field-value">{item.value}</p>
                      {item.detail && <p className="field-detail">{item.detail}</p>}
                      {item.subDetail && <p className="field-detail">{item.subDetail}</p>}
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
        </div>

        <section className="section-block section-anchor" id="categories" aria-labelledby="categories-title">
          <h2 id="categories-title" className="section-header-with-icon">
            <Icon name="grid_view" />
            Key Categories
          </h2>
          <div className="category-list">
            {categories.map((category) => (
              <button className="category-list-item" type="button" key={category.label} onClick={() => scrollToSection(category.target)}>
                <Icon name={category.icon} />
                <span>{category.label}</span>
              </button>
            ))}
          </div>
        </section>

        <section className="section-block section-anchor" id="fundraising" aria-labelledby="fundraising-title">
          <h2 id="fundraising-title" className="section-header-with-icon">
            <Icon name="payments" />
            Fundraising Goals
          </h2>
          <div className="goal-list">
            {goals.map((goal) => (
              <article className="goal-card" id={goal.id} key={goal.id}>
                <div className="goal-card__image">
                  <img src={goal.image} alt={goal.imageAlt} loading="lazy" />
                </div>
                <div className="goal-card__body">
                  <div>
                    <div className="goal-card__heading">
                      <div style={{ width: '100%' }}>
                        {goal.category && <p className="goal-category-badge" style={{ marginBottom: '6px' }}>{goal.category}</p>}
                        <h3 style={{ margin: 0 }}>{goal.title}</h3>
                        <p className="goal-subtitle" style={{ fontSize: '14px', color: 'var(--on-surface-variant)', margin: '4px 0 0 0' }}>{goal.subtitle}</p>
                      </div>
                    </div>

                    <div className="progress-area" aria-label={`${goal.progress}% complete`} style={{ marginTop: '20px' }}>
                      <div className="progress-track" role="progressbar" aria-valuemin={0} aria-valuemax={100} aria-valuenow={goal.progress} style={{ marginBottom: '12px' }}>
                        <span style={{ width: `${goal.progress}%` }} />
                      </div>
                      <div className="progress-labels" style={{ flexDirection: 'column', gap: '4px' }}>
                        <div style={{ display: 'flex', justifyContent: 'space-between', fontSize: '11px' }}>
                          <span><strong>{goal.progressAmount}</strong> {goal.progressLabel.toLowerCase()} of <strong>{goal.targetAmount}</strong> goal</span>
                        </div>
                      </div>
                    </div>

                    <div className="goal-context" style={{ display: 'flex', flexDirection: 'column', gap: '4px', marginTop: '12px', fontSize: '11px', color: 'var(--on-surface-variant)', fontWeight: 500 }}>
                       {goal.status && <span>Current Stage: <strong style={{ color: 'var(--on-surface)' }}>{goal.status}</strong></span>}
                       {goal.deadline && <span>Target Date: <strong style={{ color: 'var(--on-surface)' }}>{goal.deadline}</strong></span>}
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
                      onClick={() => goal.id === "bosse-plus-two" ? window.location.href = "/goal-details.html" : announceComingSoon(goal.title)}
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
          <h2 id="updates-title" className="section-header-with-icon">
            <Icon name="campaign" />
            Latest Updates
          </h2>
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
              <button
                className={`nav-item${isActive ? " nav-item--active" : ""}`}
                type="button"
                key={item.label}
                onClick={() => {
                  if (item.url) {
                    if (item.url.startsWith("/")) {
                      window.location.href = item.url;
                    } else {
                      scrollToSection(item.target);
                    }
                  } else {
                    scrollToSection(item.target);
                  }
                }}
                aria-current={isActive ? "page" : undefined}
              >
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

  if (route.startsWith(`#${plusTwoGoalRoute}`)) {
    window.location.replace("/goal-details.html");
    return null;
  }

  return <HomePage />;
}
