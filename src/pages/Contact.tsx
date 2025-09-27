import { Mail, Phone, MapPin, MessageCircle, Clock, CheckCircle } from "lucide-react"
import { Button } from "@/components/ui/button"
import { ContactForm } from "@/components/contact-form"
import { SEOHead } from "@/components/seo-head"

export default function Contact() {
  return (
    <>
      <SEOHead 
        title="Contact Adil GFX - Get Your Design Project Started Today"
        description="Ready to transform your brand? Contact professional designer Adil for logo design, YouTube thumbnails, and video editing. Free consultation and 24-hour response guaranteed."
        keywords="contact designer, hire logo designer, youtube thumbnail designer, video editor contact, design consultation, professional design services"
        url="https://adilgfx.com/contact"
      />
      <main className="pt-24 pb-20">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          {/* Page header */}
          <div className="text-center mb-16">
            <h1 className="text-4xl sm:text-5xl font-bold text-foreground mb-6">
              Let's Create Something <span className="text-gradient-youtube">Amazing</span>
            </h1>
            <p className="text-xl text-muted-foreground max-w-3xl mx-auto">
              Ready to transform your brand? Get in touch and let's discuss how we can bring your vision to life.
            </p>
          </div>

          <div className="grid grid-cols-1 lg:grid-cols-3 gap-12">
            {/* Contact info & Quick chat */}
            <div className="space-y-8">
              <div className="card-premium">
                <h3 className="text-xl font-semibold text-foreground mb-6">Get In Touch</h3>
                
                <div className="space-y-6">
                  <div className="flex items-center space-x-3">
                    <div className="w-10 h-10 bg-gradient-youtube rounded-lg flex items-center justify-center">
                      <Mail className="h-5 w-5 text-white" />
                    </div>
                    <div>
                      <div className="font-medium text-foreground">Email</div>
                      <a href="mailto:hello@adilgfx.com" className="text-muted-foreground hover:text-youtube-red transition-smooth">
                        hello@adilgfx.com
                      </a>
                    </div>
                  </div>
                  
                  <div className="flex items-center space-x-3">
                    <div className="w-10 h-10 bg-gradient-youtube rounded-lg flex items-center justify-center">
                      <Phone className="h-5 w-5 text-white" />
                    </div>
                    <div>
                      <div className="font-medium text-foreground">WhatsApp</div>
                      <a href="https://wa.me/1234567890" className="text-muted-foreground hover:text-youtube-red transition-smooth">
                        Quick Chat Available
                      </a>
                    </div>
                  </div>
                  
                  <div className="flex items-center space-x-3">
                    <div className="w-10 h-10 bg-gradient-youtube rounded-lg flex items-center justify-center">
                      <Clock className="h-5 w-5 text-white" />
                    </div>
                    <div>
                      <div className="font-medium text-foreground">Response Time</div>
                      <div className="text-muted-foreground">Within 24 hours</div>
                    </div>
                  </div>
                </div>

                <div className="mt-8 pt-6 border-t border-border">
                  <Button 
                    className="w-full bg-gradient-youtube hover:shadow-glow transition-all duration-300 font-medium"
                    onClick={() => window.open('https://wa.me/1234567890', '_blank')}
                  >
                    <MessageCircle className="mr-2 h-4 w-4" />
                    Quick WhatsApp Chat
                  </Button>
                </div>
              </div>

              {/* What to expect */}
              <div className="card-premium">
                <h3 className="text-xl font-semibold text-foreground mb-6">What to Expect</h3>
                
                <div className="space-y-4">
                  <div className="flex items-start space-x-3">
                    <CheckCircle className="h-5 w-5 text-green-500 mt-0.5 flex-shrink-0" />
                    <div>
                      <div className="font-medium text-foreground">Free Consultation</div>
                      <div className="text-sm text-muted-foreground">
                        I'll discuss your project needs and provide expert advice at no cost.
                      </div>
                    </div>
                  </div>
                  
                  <div className="flex items-start space-x-3">
                    <CheckCircle className="h-5 w-5 text-green-500 mt-0.5 flex-shrink-0" />
                    <div>
                      <div className="font-medium text-foreground">Custom Proposal</div>
                      <div className="text-sm text-muted-foreground">
                        Detailed project timeline, pricing, and deliverables tailored to your needs.
                      </div>
                    </div>
                  </div>
                  
                  <div className="flex items-start space-x-3">
                    <CheckCircle className="h-5 w-5 text-green-500 mt-0.5 flex-shrink-0" />
                    <div>
                      <div className="font-medium text-foreground">Fast Turnaround</div>
                      <div className="text-sm text-muted-foreground">
                        Most projects completed within 24-48 hours of approval.
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            {/* Contact form */}
            <div className="lg:col-span-2">
              <div className="card-premium">
                <h3 className="text-xl font-semibold text-foreground mb-6">Project Details</h3>
                <ContactForm />
              </div>
            </div>
          </div>

          {/* FAQ section */}
          <div className="mt-20">
            <div className="text-center mb-12">
              <h2 className="text-3xl font-bold text-foreground mb-4">
                Common <span className="text-gradient-youtube">Questions</span>
              </h2>
              <p className="text-muted-foreground">
                Quick answers to help you get started
              </p>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
              <div className="bg-gradient-subtle rounded-xl p-6">
                <h4 className="font-semibold text-foreground mb-2">How fast can you deliver?</h4>
                <p className="text-sm text-muted-foreground">
                  Most logos and thumbnails are completed within 24-48 hours. Video editing takes 3-7 days depending on complexity.
                </p>
              </div>
              
              <div className="bg-gradient-subtle rounded-xl p-6">
                <h4 className="font-semibold text-foreground mb-2">Do you offer revisions?</h4>
                <p className="text-sm text-muted-foreground">
                  Yes! All packages include 2-5 free revisions. I want to make sure you're 100% satisfied with the final result.
                </p>
              </div>
              
              <div className="bg-gradient-subtle rounded-xl p-6">
                <h4 className="font-semibold text-foreground mb-2">What file formats do you provide?</h4>
                <p className="text-sm text-muted-foreground">
                  You'll receive high-resolution PNG, JPG files, and source files (PSD/AI) for full commercial use.
                </p>
              </div>
            </div>
          </div>

          {/* Social proof */}
          <div className="mt-16 text-center">
            <div className="bg-gradient-subtle rounded-2xl p-8">
              <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                  <div className="text-3xl font-bold text-foreground">500+</div>
                  <div className="text-muted-foreground">Happy Clients</div>
                </div>
                <div>
                  <div className="text-3xl font-bold text-foreground">24-48h</div>
                  <div className="text-muted-foreground">Average Delivery</div>
                </div>
                <div>
                  <div className="text-3xl font-bold text-foreground">5.0★</div>
                  <div className="text-muted-foreground">Client Rating</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>
    </>
  )
}