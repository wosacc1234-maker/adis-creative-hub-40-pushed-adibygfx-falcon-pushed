// Services Management JavaScript for Admin Panel

class ServicesManager {
    constructor() {
        this.apiBase = '../api';
        this.currentService = null;
        this.init();
    }
    
    init() {
        this.loadServices();
        this.bindEvents();
    }
    
    bindEvents() {
        // Add new service button
        document.addEventListener('click', (e) => {
            if (e.target.matches('#add-service-btn')) {
                this.showAddServiceModal();
            }
            
            if (e.target.matches('.edit-service-btn')) {
                const serviceId = e.target.dataset.serviceId;
                this.editService(serviceId);
            }
            
            if (e.target.matches('.delete-service-btn')) {
                const serviceId = e.target.dataset.serviceId;
                this.deleteService(serviceId);
            }
        });
    }
    
    async loadServices() {
        try {
            const response = await fetch(`${this.apiBase}/services`);
            const data = await response.json();
            
            this.renderServicesTable(data.services);
        } catch (error) {
            console.error('Error loading services:', error);
        }
    }
    
    renderServicesTable(services) {
        const tableBody = document.getElementById('services-table-body');
        
        tableBody.innerHTML = services.map(service => `
            <tr>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-gradient-to-r from-red-500 to-orange-500 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-${this.getIconClass(service.icon)} text-white"></i>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-gray-900">${service.title}</div>
                            <div class="text-sm text-gray-500">${service.description.substring(0, 50)}...</div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">${service.price}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${service.popular ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800'}">
                        ${service.popular ? 'Popular' : 'Standard'}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${service.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                        ${service.is_active ? 'Active' : 'Inactive'}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <button class="edit-service-btn text-indigo-600 hover:text-indigo-900 mr-3" data-service-id="${service.id}">Edit</button>
                    <button class="delete-service-btn text-red-600 hover:text-red-900" data-service-id="${service.id}">Delete</button>
                </td>
            </tr>
        `).join('');
    }
    
    showAddServiceModal() {
        const modal = this.createServiceModal();
        document.body.appendChild(modal);
    }
    
    createServiceModal(service = null) {
        const isEdit = service !== null;
        const modalId = 'service-modal';
        
        const modal = document.createElement('div');
        modal.id = modalId;
        modal.className = 'fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4';
        
        modal.innerHTML = `
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-semibold text-gray-800">
                        ${isEdit ? 'Edit Service' : 'Add New Service'}
                    </h3>
                </div>
                
                <form id="service-form" class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Service Title *</label>
                            <input 
                                type="text" 
                                name="title" 
                                value="${service?.title || ''}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500"
                                required
                            >
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">URL Slug *</label>
                            <input 
                                type="text" 
                                name="slug" 
                                value="${service?.slug || ''}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500"
                                required
                            >
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea 
                            name="description" 
                            rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500"
                        >${service?.description || ''}</textarea>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Icon</label>
                            <select 
                                name="icon"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500"
                            >
                                <option value="Palette" ${service?.icon === 'Palette' ? 'selected' : ''}>Palette (Design)</option>
                                <option value="Play" ${service?.icon === 'Play' ? 'selected' : ''}>Play (Video)</option>
                                <option value="Zap" ${service?.icon === 'Zap' ? 'selected' : ''}>Zap (Fast)</option>
                                <option value="Plus" ${service?.icon === 'Plus' ? 'selected' : ''}>Plus (Add)</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Price Display</label>
                            <input 
                                type="text" 
                                name="price" 
                                value="${service?.price || ''}"
                                placeholder="From $149"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500"
                            >
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Features (one per line)</label>
                        <textarea 
                            name="features" 
                            rows="6"
                            placeholder="3 Concepts&#10;Unlimited Revisions&#10;All File Formats&#10;Copyright Transfer"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500"
                        >${service?.features?.map(f => f.text).join('\n') || ''}</textarea>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="flex items-center">
                            <input 
                                type="checkbox" 
                                name="popular" 
                                id="popular"
                                ${service?.popular ? 'checked' : ''}
                                class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded"
                            >
                            <label for="popular" class="ml-2 block text-sm text-gray-900">
                                Mark as Popular
                            </label>
                        </div>
                        
                        <div class="flex items-center">
                            <input 
                                type="checkbox" 
                                name="is_active" 
                                id="is_active"
                                ${service?.is_active !== false ? 'checked' : ''}
                                class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded"
                            >
                            <label for="is_active" class="ml-2 block text-sm text-gray-900">
                                Active
                            </label>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Sort Order</label>
                            <input 
                                type="number" 
                                name="sort_order" 
                                value="${service?.sort_order || 0}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500"
                            >
                        </div>
                    </div>
                </form>
                
                <div class="p-6 border-t flex justify-end space-x-3">
                    <button 
                        type="button" 
                        onclick="this.closest('.fixed').remove()"
                        class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50"
                    >
                        Cancel
                    </button>
                    <button 
                        type="button" 
                        onclick="servicesManager.saveService(${service?.id || 'null'})"
                        class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600"
                    >
                        ${isEdit ? 'Update Service' : 'Create Service'}
                    </button>
                </div>
            </div>
        `;
        
        return modal;
    }
    
    async saveService(serviceId = null) {
        const form = document.getElementById('service-form');
        const formData = new FormData(form);
        
        // Convert features from textarea to array
        const featuresText = formData.get('features');
        const features = featuresText ? featuresText.split('\n').filter(f => f.trim()).map(f => ({
            text: f.trim(),
            included: true
        })) : [];
        
        const serviceData = {
            title: formData.get('title'),
            slug: formData.get('slug'),
            description: formData.get('description'),
            icon: formData.get('icon'),
            price: formData.get('price'),
            features: features,
            popular: formData.get('popular') === 'on',
            is_active: formData.get('is_active') === 'on',
            sort_order: parseInt(formData.get('sort_order')) || 0
        };
        
        try {
            const url = serviceId ? `${this.apiBase}/services/${serviceId}` : `${this.apiBase}/services`;
            const method = serviceId ? 'PUT' : 'POST';
            
            const response = await fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(serviceData)
            });
            
            const result = await response.json();
            
            if (response.ok) {
                // Close modal
                document.getElementById('service-modal').remove();
                
                // Reload services
                this.loadServices();
                
                // Show success message
                this.showNotification('Service saved successfully!', 'success');
            } else {
                this.showNotification(result.message || 'Error saving service', 'error');
            }
        } catch (error) {
            console.error('Error saving service:', error);
            this.showNotification('Error saving service', 'error');
        }
    }
    
    async editService(serviceId) {
        try {
            const response = await fetch(`${this.apiBase}/services/${serviceId}`);
            const data = await response.json();
            
            if (response.ok) {
                const modal = this.createServiceModal(data.service);
                document.body.appendChild(modal);
            }
        } catch (error) {
            console.error('Error loading service:', error);
        }
    }
    
    async deleteService(serviceId) {
        if (!confirm('Are you sure you want to delete this service?')) {
            return;
        }
        
        try {
            const response = await fetch(`${this.apiBase}/services/${serviceId}`, {
                method: 'DELETE'
            });
            
            if (response.ok) {
                this.loadServices();
                this.showNotification('Service deleted successfully!', 'success');
            } else {
                const result = await response.json();
                this.showNotification(result.message || 'Error deleting service', 'error');
            }
        } catch (error) {
            console.error('Error deleting service:', error);
            this.showNotification('Error deleting service', 'error');
        }
    }
    
    getIconClass(iconName) {
        const iconMap = {
            'Palette': 'palette',
            'Play': 'play',
            'Zap': 'bolt',
            'Plus': 'plus'
        };
        return iconMap[iconName] || 'cog';
    }
    
    showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 p-4 rounded-md shadow-lg ${
            type === 'success' ? 'bg-green-500 text-white' : 
            type === 'error' ? 'bg-red-500 text-white' : 
            'bg-blue-500 text-white'
        }`;
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }
}

// Initialize services manager
const servicesManager = new ServicesManager();