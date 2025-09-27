import { Award, Users, Clock, Zap, Play, Calendar, ArrowRight } from "lucide-react"
import { Button } from "@/components/ui/button"
import { SEOHead } from "@/components/seo-head"

const tools = [
  { name: "Adobe Photoshop", level: "Expert", years: "8+" },
  { name: "Adobe Illustrator", level: "Expert", years: "7+" },
  { name: "Adobe Premiere Pro", level: "Advanced", years: "6+" },
  { name: "After Effects", level: "Advanced", years: "5+" },
  { name: "Figma", level: "Advanced", years: "4+" },
  { name: "AI Tools (MidJourney, etc.)", level: "Advanced", years: "2+" }
]

const socialProof = [
  { icon: Users, value: "500+", label: "Happy Clients" },
  { icon: Clock, value: "24-48h", label: "Average Delivery" },
  { icon: Award, value: "5.0★", label: "Average Rating" },
  { icon: Zap, value: "99%", label: "Client Retention" }
]

const philosophy = [
  {
    icon: Award,
    title: "Results-Driven",
    description: "Every design is crafted to achieve your specific goals and drive measurable results."
  },
  {
    icon: Clock,
    title: "Fast & Reliable",
    description: "Quick turnaround times with consistent quality you can count on."
  },
  {
    icon: Zap,
    title: "True Partnership",
    description: "I work closely with you to understand your vision and bring it to life."
  }
]

export default function About() {
  return (
    <>
      <SEOHead 
        title="About Adil GFX - Professional Designer & Creative Expert"
        description="Meet Adil, a professional designer with 8+ years of experience in logo design, YouTube thumbnails, and video editing. Trusted by 500+ clients worldwide."
        keywords="about adil gfx, professional designer, logo designer, youtube thumbnail creator, video editor, creative expert"
        url="https://adilgfx.com/about"
      />
      <main className="pt-20 pb-16">
        <div className="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
          
          {/* Intro Section */}
          <div className="text-center mb-16">
            <h1 className="text-4xl sm:text-5xl lg:text-6xl font-bold text-foreground mb-8">
              Hi, I'm <span className="text-gradient-youtube">Adil</span>
            </h1>
            
            {/* Video Placeholder */}
            <div className="max-w-2xl mx-auto mb-8">
              <div className="relative aspect-video bg-gradient-subtle rounded-2xl shadow-premium overflow-hidden border border-border">
                <div className="absolute inset-0 flex items-center justify-center">
                  <div className="text-center">
                    <div className="w-20 h-20 bg-gradient-youtube rounded-full flex items-center justify-center mx-auto mb-4">
                      <Play className="h-10 w-10 text-white ml-1" />
                    </div>
                    <p className="text-muted-foreground text-sm">Personal Introduction Video</p>
                    <p className="text-xs text-muted-foreground mt-1">Coming Soon</p>
                  </div>
                </div>
              </div>
            </div>
            
            <p className="text-lg sm:text-xl text-muted-foreground max-w-3xl mx-auto mb-12">
              8+ years helping brands and YouTubers transform their visual identity with designs that deliver results. 
              From concept to conversion, I create visuals that make an impact.
            </p>
            
            {/* Social Proof */}
            <div className="grid grid-cols-2 lg:grid-cols-4 gap-6 max-w-4xl mx-auto">
              {socialProof.map((stat, index) => {
                const Icon = stat.icon
                return (
                  <div key={index} className="text-center p-4 bg-card rounded-xl border border-border">
                    <div className="w-12 h-12 bg-gradient-youtube rounded-xl flex items-center justify-center mx-auto mb-3">
                      <Icon className="h-6 w-6 text-white" />
                    </div>
                    <div className="text-2xl sm:text-3xl font-bold text-foreground mb-1">{stat.value}</div>
                    <div className="text-xs sm:text-sm text-muted-foreground">{stat.label}</div>
                  </div>
                )
              })}
            </div>
          </div>

          {/* Story Section */}
          <div className="mb-20">
            <div className="text-center mb-12">
              <h2 className="text-3xl sm:text-4xl font-bold text-foreground mb-4">
                My Design <span className="text-gradient-youtube">Journey</span>
              </h2>
            </div>
            <div className="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
              <div className="space-y-6 text-muted-foreground">
                <p className="text-base sm:text-lg">
                  What started as a passion for visual storytelling has evolved into a results-driven design practice 
                  that's transformed hundreds of brands and helped creators achieve breakthrough growth.
                </p>
                <p className="text-base sm:text-lg">
                  I don't just create beautiful designs—I craft strategic visuals that understand your audience, 
                  solve real problems, and drive measurable results. Every logo builds trust, every thumbnail 
                  increases clicks, and every video converts viewers into loyal customers.
                </p>
                <p className="text-base sm:text-lg">
                  My approach combines creative intuition with data-driven insights, ensuring that we're not just 
                  making things look good, but making them perform exceptionally for your specific goals.
                </p>
              </div>
              
              <div className="bg-gradient-subtle rounded-2xl p-8 shadow-premium">
                <h3 className="text-xl font-semibold text-foreground mb-6 text-center">Notable Achievements</h3>
                <div className="space-y-4 text-sm">
                  <div className="flex items-center gap-3">
                    <div className="w-2 h-2 bg-gradient-youtube rounded-full"></div>
                    <span className="text-muted-foreground">Helped 100+ YouTubers increase CTR by 40%+</span>
                  </div>
                  <div className="flex items-center gap-3">
                    <div className="w-2 h-2 bg-gradient-youtube rounded-full"></div>
                    <span className="text-muted-foreground">Designed logos for startups that raised $50M+</span>
                  </div>
                  <div className="flex items-center gap-3">
                    <div className="w-2 h-2 bg-gradient-youtube rounded-full"></div>
                    <span className="text-muted-foreground">Created viral content viewed by millions</span>
                  </div>
                  <div className="flex items-center gap-3">
                    <div className="w-2 h-2 bg-gradient-youtube rounded-full"></div>
                    <span className="text-muted-foreground">Maintained 99% client retention rate</span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          {/* Expertise Section */}
          <div className="mb-20">
            <div className="text-center mb-12">
              <h2 className="text-3xl sm:text-4xl font-bold text-foreground mb-4">
                My <span className="text-gradient-youtube">Expertise</span>
              </h2>
              <p className="text-muted-foreground max-w-2xl mx-auto">
                Mastery across the creative spectrum, from traditional design tools to cutting-edge AI workflows.
              </p>
            </div>
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 max-w-4xl mx-auto">
              {tools.map((tool, index) => (
                <div key={index} className="flex items-center justify-between p-4 bg-card rounded-xl border border-border hover:border-youtube-red/30 transition-colors">
                  <div>
                    <div className="font-medium text-foreground text-sm sm:text-base">{tool.name}</div>
                    <div className="text-xs text-muted-foreground">{tool.years} experience</div>
                  </div>
                  <span className="px-3 py-1 bg-gradient-youtube text-white text-xs rounded-full font-medium">
                    {tool.level}
                  </span>
                </div>
              ))}
            </div>
          </div>

          {/* Philosophy Section */}
          <div className="mb-20">
            <div className="text-center mb-12">
              <h2 className="text-3xl sm:text-4xl font-bold text-foreground mb-4">
                My <span className="text-gradient-youtube">Philosophy</span>
              </h2>
              <p className="text-muted-foreground max-w-2xl mx-auto">
                Three core principles that guide every project and client relationship.
              </p>
            </div>
            <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
              {philosophy.map((item, index) => {
                const Icon = item.icon
                return (
                  <div key={index} className="text-center p-6 bg-card rounded-xl border border-border">
                    <div className="w-16 h-16 bg-gradient-youtube rounded-2xl flex items-center justify-center mx-auto mb-4">
                      <Icon className="h-8 w-8 text-white" />
                    </div>
                    <h3 className="text-xl font-semibold text-foreground mb-3">{item.title}</h3>
                    <p className="text-muted-foreground text-sm">{item.description}</p>
                  </div>
                )
              })}
            </div>
          </div>

          {/* Call to Action */}
          <div className="text-center bg-gradient-subtle rounded-2xl p-8 lg:p-12 shadow-premium">
            <h2 className="text-3xl sm:text-4xl font-bold text-foreground mb-4">
              Ready to Transform Your <span className="text-gradient-youtube">Brand?</span>
            </h2>
            <p className="text-muted-foreground text-lg max-w-2xl mx-auto mb-8">
              Let's discuss your project and create designs that don't just look amazing, but deliver the results you need.
            </p>
            <div className="flex flex-col sm:flex-row gap-4 justify-center">
              <Button className="bg-gradient-youtube hover:shadow-glow text-white" size="lg">
                <ArrowRight className="h-5 w-5 mr-2" />
                Start Your Project
              </Button>
              <Button variant="outline" size="lg" className="border-youtube-red text-youtube-red hover:bg-youtube-red hover:text-white">
                <Calendar className="h-5 w-5 mr-2" />
                Schedule a Call
              </Button>
            </div>
          </div>

        </div>
      </main>
    </>
  )
}