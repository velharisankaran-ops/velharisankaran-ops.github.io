import { useEffect, useRef, useState } from "react";
import { navigation, plusTwoGoal } from "./content";

function Icon({ name, filled = false }: { name: string; filled?: boolean }) {
  return (
    <span className={`material-symbols-outlined${filled ? " material-symbols-outlined--filled" : ""}`} aria-hidden="true">
      {name}
    </span>
  );
}

function goHome(target?: string) {
  window.location.hash = target ? target : "";
}

export function GoalDetailsPage() {
  const [notice, setNotice] = useState("");
  const timer = useRef<number | undefined>(undefined);
  const goal = plusTwoGoal;

  const announce = (message: string) => {
    window.clearTimeout(timer.current);
    setNotice(message);
    timer.current = window.setTimeout(() => setNotice(""), 3200);
  };

  const shareGoal = async () => {
    const shareData = {
      title: `${goal.title} — Velhari Sankaran`,
      text: `Support Velhari Sankaran's ${goal.category.toLowerCase()} goal.`,
      url: window.location.href,
    };
    try {
      if (navigator.share) {
        await navigator.share(shareData);
      } else {
        await navigator.clipboard.writeText(window.location.href);
        announce("Goal link copied to your clipboard.");
      }
    } catch (error) {
      if ((error as DOMException).name !== "AbortError") announce("Sharing is unavailable right now.");
    }
  };

  useEffect(() => {
    const previousTitle = document.title;
    document.title = "Goal Details - Velhari";
    return () => {
      document.title = previousTitle;
      window.clearTimeout(timer.current);
    };
  }, []);

  return (
    <div className="app-shell goal-details-shell">
      <a className="skip-link" href="#goal-overview">Skip to goal details</a>

      <header className="goal-top-bar">
        <div className="goal-top-bar__inner">
          <div className="goal-top-bar__left">
            <button className="icon-button" type="button" aria-label="Go back" onClick={() => history.length > 1 ? history.back() : goHome()}>
              <Icon name="arrow_back" />
            </button>
            <div className="goal-top-bar__title">
              <Icon name="account_balance_wallet" />
              <h1>Fundraising</h1>
            </div>
          </div>
          <div className="goal-top-bar__right">
            <img src="/profile.jpg" alt="Velhari Sankaran" className="header-profile-img" />
            <button className="icon-button goal-top-bar__notification" type="button" aria-label="Notifications" onClick={() => announce("Updates are available from the Home dashboard.")}>
              <Icon name="notifications" />
            </button>
          </div>
        </div>
      </header>

      <main className="goal-details-main">
        <section className="goal-hero" aria-label={`${goal.title} education goal`}>
          <img src={goal.heroImage} alt="BOSSE commerce classroom and study auditorium" />
          <div className="goal-hero__shade" />
          <div className="goal-hero__content">
            <span className="goal-category"><Icon name="verified" filled />{goal.category}</span>
          </div>
        </section>

        <div className="goal-details-content" id="goal-overview">
          <section className="goal-progress-card" aria-labelledby="goal-title">
            <div className="goal-progress-card__heading">
              <span className="goal-school-icon"><Icon name="school" filled /></span>
              <div>
                <h2 id="goal-title">{goal.title}</h2>
                <p>{goal.subtitle}</p>
              </div>
            </div>
            <div className="goal-progress-card__summary">
              <div>
                <span className="goal-kicker">Fundraising Progress</span>
                <p><strong>{goal.raisedAmount}</strong> <span>of {goal.targetAmount}</span></p>
              </div>
              <span className="supporter-badge"><Icon name="group" />{goal.supporters} supporters</span>
            </div>
            <div className="goal-detail-progress" role="progressbar" aria-label="Fundraising progress" aria-valuemin={0} aria-valuemax={100} aria-valuenow={goal.progress}>
              <span style={{ width: `${goal.progress}%` }} />
            </div>
            <div className="goal-progress-card__labels"><strong>{goal.progress}% reached</strong><span>{goal.remainingAmount} to go</span></div>
          </section>

          <section className="goal-detail-section" aria-labelledby="fee-title">
            <div className="goal-detail-section__heading">
              <Icon name="receipt_long" />
              <h2 id="fee-title">Fee Breakdown</h2>
              <span>{goal.invoice}</span>
            </div>
            <div className="data-table-card">
              <table>
                <thead><tr><th>Component</th><th>Amount</th></tr></thead>
                <tbody>
                  {goal.feeBreakdown.map((row) => <tr key={row.label}><td><strong>{row.label}</strong><small>{row.detail}</small></td><td>{row.value}</td></tr>)}
                  <tr className="grand-total"><td>Grand Total</td><td>{goal.targetAmount}</td></tr>
                </tbody>
              </table>
            </div>
            <p className="table-note">* Amounts as per the official Bill of Supply provided by AMET Global.</p>
          </section>

          <section className="goal-detail-section goal-detail-section--spaced" aria-labelledby="enrollment-title">
            <div className="goal-detail-section__heading">
              <Icon name="assignment" />
              <h2 id="enrollment-title">Enrollment Details Summary</h2>
            </div>
            <div className="data-table-card data-table-card--details">
              <table>
                <thead><tr><th>Detail</th><th>Information</th></tr></thead>
                <tbody>{goal.enrollmentDetails.map((row) => <tr key={row.label}><td><strong>{row.label}</strong></td><td className={row.label === "Official Website" ? "website-value" : undefined}>{row.value}</td></tr>)}</tbody>
              </table>
            </div>
          </section>

          <section className="goal-detail-section goal-detail-section--spaced" aria-labelledby="ledger-title">
            <div className="goal-detail-section__heading">
              <Icon name="group" />
              <h2 id="ledger-title">Contributor Ledger</h2>
            </div>
            <div className="data-table-card">
              <table>
                <thead><tr><th>Contributor</th><th>Amount</th></tr></thead>
                <tbody>
                  {goal.contributors.map((row) => <tr key={row.label}><td><strong>{row.label}</strong></td><td>{row.value}</td></tr>)}
                  <tr className="empty-row"><td colSpan={2}>Waiting for more backers...</td></tr>
                </tbody>
              </table>
            </div>
          </section>

          <section className="goal-detail-section goal-detail-section--spaced" aria-labelledby="milestones-title">
            <div className="goal-detail-section__heading">
              <Icon name="flag" />
              <h2 id="milestones-title">Key Milestones</h2>
            </div>
            <div className="milestone-list">
              {goal.milestones.map((milestone) => (
                <article className={`milestone-card${milestone.state === "pending" ? " milestone-card--pending" : ""}`} key={milestone.title}>
                  <div className="milestone-card__icon">
                    <Icon name={milestone.state === "complete" ? "check" : "pending"} />
                  </div>
                  <div><h3>{milestone.title}</h3><p>{milestone.description}</p></div>
                </article>
              ))}
            </div>
          </section>

          <section className="goal-detail-section support-section" aria-labelledby="support-title">
            <div className="goal-detail-section__heading">
              <Icon name="favorite" />
              <h2 id="support-title">Support Options</h2>
            </div>
            <div className="support-actions">
              <button className="support-button support-button--primary" type="button" onClick={() => announce("Direct supporter checkout is coming next. You can pay the institution directly below.")}><Icon name="favorite" />Support This Goal</button>
              <a className="support-button support-button--outline" href={goal.institutionPaymentUrl} target="_blank" rel="noreferrer"><Icon name="account_balance" />Pay Institution Directly</a>
              <button className="support-button support-button--quiet" type="button" onClick={shareGoal}><Icon name="share" />Share</button>
            </div>
          </section>
        </div>
      </main>

      <nav className="bottom-nav" aria-label="Primary navigation">
        <div className="bottom-nav__inner">
          {navigation.map((item) => {
            const isJourney = item.label === "Journey";
            return <button className={`nav-item${isJourney ? " nav-item--active" : ""}`} type="button" key={item.label} onClick={() => goHome(item.target)} aria-current={isJourney ? "page" : undefined}><span className="nav-item__icon"><Icon name={item.label === "Home" ? "home" : item.label === "Explore" ? "search" : item.label === "Updates" ? "mail" : item.icon} filled={isJourney} />{item.hasUnread && <span className="unread-dot" aria-hidden="true" />}</span><span>{item.label}</span></button>;
          })}
        </div>
      </nav>

      <div className={`toast${notice ? " toast--visible" : ""}`} role="status" aria-live="polite" aria-atomic="true">{notice}</div>
    </div>
  );
}
