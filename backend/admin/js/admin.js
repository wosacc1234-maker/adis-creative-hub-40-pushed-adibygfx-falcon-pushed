// Admin Panel JavaScript
class AdminPanel {
    constructor() {
        this.currentSection = 'dashboard';
        this.apiBase = '../api';
        this.init();
    }
    
    init() {
        this.bindEvents();
        this.loadDashboard();
    }
    
    bindEvents() {
        // Sidebar navigation
        document.querySelectorAll('.sidebar-link').forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const section = link.getAttribute('href').substring(1);
                this.navigateToSection(section);
            });
        });
    }
    
    navigateToSection(section) {
        // Update active link
        document.querySelectorAll('.sidebar-link').forEach(link => {
            link.classList.remove('active');
        });
        document.querySelector(`[href="#${section}"]`).classList.add('active');
        
        // Update page title
        const titles = {
            dashboard: 'Dashboard',
            pages: 'Pages Management',
            portfolio: 'Portfolio Management',
            services: 'Services Management',
            blog: 'Blog Management',
            testimonials: 'Testimonials Management',
            forms: 'Forms & Leads',
            media: 'Media Library',
            settings: 'Settings',
            users: 'User Management'
        };
        
        document.getElementById('page-title').textContent = titles[section] || section;
        
        // Load section content
        this.currentSection = section;
        this.loadSectionContent(section);
    }
    
    async loadSectionContent(section) {
        const contentArea = document.getElementById('dynamic-content');
        const dashboardContent = document.getElementById('dashboard-content');
        
        if (section === 'dashboard') {
            dashboardContent.style.display = 'block';
            contentArea.style.display = 'none';
            this.loadDashboard();
            return;
        }
        
        dashboardContent.style.display = 'none';
        contentArea.style.display = 'block';
        
        try {
            switch (section) {
                case 'pages':
                    await this.loadPagesSection();
                    break;
                case 'portfolio':
                    await this.loadPortfolioSection();
                    break;
                case 'services':
                    await this.loadServicesSection();
                    break;
                case 'blog':
                    await this.loadBlogSection();
                    break;
                case 'testimonials':
                    await this.loadTestimonialsSection();
                    break;
                case 'forms':
                    await this.loadFormsSection();
                    break;
                case 'media':
                    await this.loadMediaSection();
                    break;
                case 'settings':
                    await this.loadSettingsSection();
                    break;
                case 'users':
                    await this.loadUsersSection();
                    break;
                default:
                    contentArea.innerHTML = '<p class="text-gray-600">Section not implemented yet.</p>';
            }
        } catch (error) {
            console.error('Error loading section:', error);
            contentArea.innerHTML = '<p class="text-red-600">Error loading content. Please try again.</p>';
        }
    }
    
    async loadDashboard() {
        try {
            // Load form stats
            const formsResponse = await fetch(`${this.apiBase}/forms/stats`);
            if (formsResponse.ok) {
                const formsData = await formsResponse.json();
                document.getElementById('total-submissions').textContent = formsData.stats.total_submissions || 0;
            }
            
            // Load portfolio stats
            const portfolioResponse = await fetch(`${this.apiBase}/portfolio`);
            if (portfolioResponse.ok) {
                const portfolioData = await portfolioResponse.json();
                document.getElementById('total-projects').textContent = portfolioData.projects.length || 0;
            }
            
            // Load blog stats
            const blogResponse = await fetch(`${this.apiBase}/blog`);
            if (blogResponse.ok) {
                const blogData = await blogResponse.json();
                document.getElementById('total-posts').textContent = blogData.posts.length || 0;
            }
            
            // Load recent activity
            this.loadRecentActivity();
            
        } catch (error) {
            console.error('Error loading dashboard:', error);
        }
    }
    
    async loadRecentActivity() {
        try {
            const response = await fetch(`${this.apiBase}/forms?limit=5`);
            if (response.ok) {
                const data = await response.json();
                const activityContainer = document.getElementById('recent-activity');
                
                if (data.submissions && data.submissions.length > 0) {
                    activityContainer.innerHTML = data.submissions.map(submission => `
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="font-medium text-gray-800">New ${submission.form_type} submission</p>
                                <p class="text-sm text-gray-600">${submission.name || 'Anonymous'} - ${submission.email || 'No email'}</p>
                            </div>
                            <span class="text-xs text-gray-500">${new Date(submission.created_at).toLocaleDateString()}</span>
                        </div>
                    `).join('');
                } else {
                    activityContainer.innerHTML = '<p class="text-gray-600">No recent activity</p>';
                }
            }
        } catch (error) {
            console.error('Error loading recent activity:', error);
        }
    }
    
    async loadPagesSection() {
        const contentArea = document.getElementById('dynamic-content');
        
        try {
            const response = await fetch(`${this.apiBase}/pages`);
            const data = await response.json();
            
            contentArea.innerHTML = `
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6 border-b flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-800">Pages Management</h3>
                        <button onclick="adminPanel.showCreatePageModal()" class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600">
                            <i class="fas fa-plus mr-2"></i>Add New Page
                        </button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Slug</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Updated</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                ${data.pages.map(page => `
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">${page.title}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500">/${page.slug}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${page.is_published ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                                                ${page.is_published ? 'Published' : 'Draft'}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            ${new Date(page.updated_at).toLocaleDateString()}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button onclick="adminPanel.editPage(${page.id})" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</button>
                                            <button onclick="adminPanel.deletePage(${page.id})" class="text-red-600 hover:text-red-900">Delete</button>
                                        </td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                </div>
            `;
        } catch (error) {
            contentArea.innerHTML = '<p class="text-red-600">Error loading pages. Please try again.</p>';
        }
    }
    
    async loadFormsSection() {
        const contentArea = document.getElementById('dynamic-content');
        
        try {
            const response = await fetch(`${this.apiBase}/forms`);
            const data = await response.json();
            
            contentArea.innerHTML = `
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6 border-b flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-800">Form Submissions & Leads</h3>
                        <div class="flex space-x-2">
                            <select id="form-type-filter" class="border border-gray-300 rounded-md px-3 py-2">
                                <option value="">All Forms</option>
                                <option value="contact">Contact</option>
                                <option value="newsletter">Newsletter</option>
                                <option value="quote">Quote Request</option>
                                <option value="consultation">Consultation</option>
                            </select>
                            <button onclick="adminPanel.exportForms()" class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">
                                <i class="fas fa-download mr-2"></i>Export CSV
                            </button>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                ${data.submissions.map(submission => `
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                ${submission.form_type}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">${submission.name || 'N/A'}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500">${submission.email || 'N/A'}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${this.getStatusColor(submission.status)}">
                                                ${submission.status}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            ${new Date(submission.created_at).toLocaleDateString()}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button onclick="adminPanel.viewSubmission(${submission.id})" class="text-indigo-600 hover:text-indigo-900 mr-3">View</button>
                                            <button onclick="adminPanel.updateSubmissionStatus(${submission.id}, 'read')" class="text-green-600 hover:text-green-900">Mark Read</button>
                                        </td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                </div>
            `;
        } catch (error) {
            contentArea.innerHTML = '<p class="text-red-600">Error loading forms. Please try again.</p>';
        }
    }
    
    getStatusColor(status) {
        const colors = {
            'new': 'bg-yellow-100 text-yellow-800',
            'read': 'bg-blue-100 text-blue-800',
            'replied': 'bg-green-100 text-green-800',
            'archived': 'bg-gray-100 text-gray-800'
        };
        return colors[status] || 'bg-gray-100 text-gray-800';
    }
    
    async exportForms() {
        try {
            const formType = document.getElementById('form-type-filter').value;
            const filters = formType ? { form_type: formType } : {};
            
            const response = await fetch(`${this.apiBase}/forms/export`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ filters })
            });
            
            if (response.ok) {
                const data = await response.json();
                // Create download link
                const link = document.createElement('a');
                link.href = `../exports/${data.filename}`;
                link.download = data.filename;
                link.click();
            }
        } catch (error) {
            console.error('Error exporting forms:', error);
            alert('Error exporting forms. Please try again.');
        }
    }
    
    async updateSubmissionStatus(id, status) {
        try {
            const response = await fetch(`${this.apiBase}/forms/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ status })
            });
            
            if (response.ok) {
                this.loadFormsSection(); // Reload the forms section
            }
        } catch (error) {
            console.error('Error updating submission status:', error);
        }
    }
    
    // Placeholder methods for other sections
    async loadPortfolioSection() {
        document.getElementById('dynamic-content').innerHTML = '<p class="text-gray-600">Portfolio management coming soon...</p>';
    }
    
    async loadServicesSection() {
        document.getElementById('dynamic-content').innerHTML = '<p class="text-gray-600">Services management coming soon...</p>';
    }
    
    async loadBlogSection() {
        document.getElementById('dynamic-content').innerHTML = '<p class="text-gray-600">Blog management coming soon...</p>';
    }
    
    async loadTestimonialsSection() {
        document.getElementById('dynamic-content').innerHTML = '<p class="text-gray-600">Testimonials management coming soon...</p>';
    }
    
    async loadMediaSection() {
        document.getElementById('dynamic-content').innerHTML = '<p class="text-gray-600">Media library coming soon...</p>';
    }
    
    async loadSettingsSection() {
        document.getElementById('dynamic-content').innerHTML = '<p class="text-gray-600">Settings management coming soon...</p>';
    }
    
    async loadUsersSection() {
        document.getElementById('dynamic-content').innerHTML = '<p class="text-gray-600">User management coming soon...</p>';
    }
}

// Initialize admin panel
const adminPanel = new AdminPanel();