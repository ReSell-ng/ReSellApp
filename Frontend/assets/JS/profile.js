function showContent(sectionId) {
        const sections = document.querySelectorAll('.contentSection');
        sections.forEach(section => section.style.display = "none");
        const activeSection = document.getElementById(sectionId);
        activeSection.style.display = 'block'
    }

