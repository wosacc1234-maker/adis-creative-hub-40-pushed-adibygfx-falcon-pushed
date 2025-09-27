import { Award, Users, Clock, Zap } from "lucide-react"
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

const achievements = [
  { icon: Users, value: "500+", label: "Happy Clients" },
  { icon: Clock, value: "24-48h", label: "Average Delivery" },
  { icon: Award, value: "5.0★", label: "Average Rating" },
  { icon: Zap, value: "99%", label: "Client Retention" }
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
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          {/* Hero Section */}
          <div className="text-center mb-16">
            <h1 className="text-4xl sm:text-5xl font-bold text-foreground mb-6">
              Meet <span className="text-gradient-youtube">Adil</span>
            </h1>
            <p className="text-xl text-muted-foreground max-w-3xl mx-auto mb-8">
              Creative professional with 8+ years of experience helping brands and content creators 
              stand out through stunning visual design that drives real results.
            </p>
          </div>

          {/* Stats */}
          <div className="grid grid-cols-2 md:grid-cols-4 gap-8 mb-20">
            {achievements.map((stat, index) => {
              const Icon = stat.icon
              return (
                <div key={index} className="text-center">
                  <div className="w-16 h-16 bg-gradient-youtube rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <Icon className="h-8 w-8 text-white" />
                  </div>
                  <div className="text-3xl font-bold text-foreground mb-2">{stat.value}</div>
                  <div className="text-sm text-muted-foreground">{stat.label}</div>
                </div>
              )
            })}
          </div>

          {/* Story Section */}
          <div className="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center mb-20">
            <div>
              <h2 className="text-3xl font-bold text-foreground mb-6">
                My Design <span className="text-gradient-youtube">Journey</span>
              </h2>
              <div className="space-y-6 text-muted-foreground">
                <p>
                  What started as a passion for visual storytelling has evolved into a thriving design practice 
                  that's helped hundreds of businesses and creators achieve their goals.
                </p>
                <p>
                  I specialize in creating designs that don't just look great—they perform. Whether it's a logo 
                  that builds instant trust, thumbnails that boost click-through rates, or videos that convert 
                  viewers into customers, every design has a purpose.
                </p>
                <p>
                  My approach combines creative intuition with data-driven insights, ensuring that every project 
                  delivers measurable results for my clients.
                </p>
              </div>
              <Button className="bg-gradient-youtube hover:shadow-glow mt-8" size="lg">
                Let's Work Together
              </Button>
            </div>
            
            <div className="relative">
              <div className="bg-gradient-subtle rounded-2xl p-8 shadow-premium">
                <h3 className="text-xl font-semibold text-foreground mb-6">My Expertise</h3>
                <div className="space-y-4">
                  {tools.map((tool, index) => (
                    <div key={index} className="flex justify-between items-center p-3 bg-card rounded-lg">
                      <div>
                        <div className="font-medium text-foreground">{tool.name}</div>
                        <div className="text-sm text-muted-foreground">{tool.years} experience</div>
                      </div>
                      <span className="px-3 py-1 bg-gradient-youtube text-white text-sm rounded-full">
                        {tool.level}
                      </span>
                    </div>
                  ))}
                </div>
              </div>
            </div>
          </div>

          {/* Mission & Values */}
          <div className="bg-gradient-subtle rounded-2xl p-8 lg:p-12">
            <div className="text-center mb-12">
              <h2 className="text-3xl font-bold text-foreground mb-4">
                My <span className="text-gradient-youtube">Mission</span>
              </h2>
              <p className="text-xl text-muted-foreground max-w-3xl mx-auto">
                To help businesses and creators build stronger connections with their audience through 
                powerful visual design that drives engagement and growth.
              </p>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
              <div className="text-center">
                <div className="w-12 h-12 bg-gradient-youtube rounded-xl flex items-center justify-center mx-auto mb-4">
                  <Award className="h-6 w-6 text-white" />
                </div>
                <h3 className="font-semibold text-foreground mb-2">Quality First</h3>
                <p className="text-sm text-muted-foreground">
                  Every design is crafted with attention to detail and professional standards.
                </p>
              </div>
              
              <div className="text-center">
                <div className="w-12 h-12 bg-gradient-youtube rounded-xl flex items-center justify-center mx-auto mb-4">
                  <Clock className="h-6 w-6 text-white" />
                </div>
                <h3 className="font-semibold text-foreground mb-2">Fast Delivery</h3>
                <p className="text-sm text-muted-foreground">
                  Quick turnaround times without compromising on quality or creativity.
                </p>
              </div>
              
              <div className="text-center">
                <div className="w-12 h-12 bg-gradient-youtube rounded-xl flex items-center justify-center mx-auto mb-4">
                  <Zap className="h-6 w-6 text-white" />
                </div>
                <h3 className="font-semibold text-foreground mb-2">Results Driven</h3>
                <p className="text-sm text-muted-foreground">
                  Designs that look great and perform even better for your business.
                </p>
              </div>
            </div>
          </div>
        </div>
      </main>
    </>
  )
}